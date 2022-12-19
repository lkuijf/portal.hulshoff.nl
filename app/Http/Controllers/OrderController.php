<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderArticle;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPromoted;
use App\Mail\OrderPlaced;

class OrderController extends Controller
{
    public function showOrders($type) {
        $t = 0;
        if($type == 'confirmed') $t = 0;
        if($type == 'reserved') $t = 1;
        $orders = Order::where('hulshoff_user_id', auth()->user()->id)
            ->where('is_reservation', $t)
            ->get();
        $data = [
            'orders' => $orders,
            'type' => $type,
        ];
        return view('orderList')->with('data', $data);
    }

    public function showOrder($id, $type) {
        $order = Order::findOr($id, function () {
            return abort(404);
        });
        if($order->hulshoff_user_id != auth()->user()->id) return abort(404); // check if order is of the current user
        if($order->is_reservation && $type != 'reserved') return abort(404); // reserved or confirmed order
        if(!$order->is_reservation && $type == 'reserved') return abort(404); // reserved or confirmed order
        return view('order')->with('order', $order);
    }

    public function newOrder(Request $request) {
        $toValidate = array(
            'deliveryDate' => 'required|date_format:d-m-Y',
            // 'deliveryHour' => 'required',
            // 'deliveryMinute' => 'required',
        );
        $validationMessages = array(
            'deliveryDate.required'=> 'Please fill in the delivery date',
            'deliveryDate.date_format'=> 'Date format must be: dd-mm-yyyy',
            // 'deliveryHour.required'=> 'Please fill in the delivery hour',
            // 'deliveryMinute.required'=> 'Please fill in the delivery minute',
        );
        $validated = $request->validate($toValidate,$validationMessages);

        $basket = [];
        if($request->session()->has('basket')) {
            $basket = session('basket');
            $request->session()->forget('basket');
        }

        $order = new Order;
        $order->hulshoff_user_id = auth()->user()->id;
        $order->is_reservation = auth()->user()->can_reserve;
        $order->orderCodeKlant = 'HUL' . date('U') - strtotime('1-1-2022') . $order->id;
        $order->afleverDatum = date("Ymd", strtotime($request->deliveryDate));
        // $order->afleverTijd = str_pad($request->deliveryHour, 2, '0', STR_PAD_LEFT) . str_pad($request->deliveryMinute, 2, '0', STR_PAD_LEFT);
        $order->afleverTijd = '0000'; // edit 15-12-2022. Delivery TIME can't be selected by customer.
        $order->save();

        foreach($basket as $id => $count) {
            $orderItem = new OrderArticle;
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $id;
            $orderItem->amount = $count;
            $orderItem->save();
        }
        
        $orderMsg = '<p>Your order has been placed</p>';
        $redirect = 'orders';
        if(auth()->user()->can_reserve) {
            $orderMsg = '<p>Your reservation has been placed</p>';
            $redirect = 'reservations';
        }
        Mail::to(auth()->user()->email)->send(new OrderPlaced(auth()->user()->can_reserve));
        $request->session()->flash('message', $orderMsg);
        return redirect()->route($redirect);
    }

    public function updateOrder(Request $request) {
        $order = Order::findOr($request->id, function () {
            return abort(404);
        });
        if($order->hulshoff_user_id != auth()->user()->id) return abort(404); // check if order is of the current user
        if($request->type == 'confirmReservation') {
            $order->is_reservation = 0;
        }
        if($request->type == 'updateDeliveryDate') {
            $order->afleverDatum = date("Ymd", strtotime($request->deliveryDate));
        }
        $order->save();

        if($request->type == 'confirmReservation') Mail::to(auth()->user()->email)->send(new OrderPromoted());

        if($request->type == 'confirmReservation') $request->session()->flash('message', '<p>Your order has been placed!</p>');
        if($request->type == 'updateDeliveryDate') $request->session()->flash('message', '<p>Delivery date has been changed</p>');
        if($request->type == 'confirmReservation') return redirect()->route('orders');
        if($request->type == 'updateDeliveryDate') return redirect()->back();
    }

    public function deleteOrder(Request $request) {
        $order = Order::findOr($request->id, function () {
            return abort(404);
        });
        if($order->hulshoff_user_id != auth()->user()->id) return abort(404); // check if order is of the current user
        $order->delete();
        $request->session()->flash('message', '<p>Reservation deleted</p>');
        return redirect()->back();
    }

    public function updateOrderArticle(Request $request) {
        $order = Order::findOr($request->o_id, function () {
            return abort(404);
        });
        if($order->hulshoff_user_id != auth()->user()->id) return abort(404); // check if order is of the current user
        $toValidate = array(
            'count' => 'required|numeric',
        );
        $validationMessages = array(
            'count.required'=> 'Please fill in a value',
            'count.numeric'=> 'Only a number is allowed',
        );
        $validated = $request->validate($toValidate,$validationMessages);

        $product = Product::find($request->p_id);
        $orderArticle = OrderArticle::where('order_id', $order->id)->where('product_id', $product->id)->first();
        if($request->count > ($product->availableAmount() + $orderArticle->amount)) { // currently reserved amount for this order must be added to the availableAmount
            return redirect()->back()->withErrors(['Cannot change to ' . $request->count . ', only ' . ($product->availableAmount() + $orderArticle->amount) . ' are available.']);
        }
        $orderArticle->amount = $request->count;
        $orderArticle->save();
        $request->session()->flash('message', '<p>Reservation updated</p>');
        return redirect()->back();
    }

    public function deleteOrderArticle(Request $request) {
        // OrderArticle::destroy($request->id);
        $ordArt = OrderArticle::find($request->id);
        $ordArt->delete();
        $totalArticles = OrderArticle::where('order_id', $ordArt->order_id)->count();
        if($totalArticles == 0) {
            Order::destroy($ordArt->order_id);
            $request->session()->flash('message', '<p>Product removed from reservation</p><p>Reservation is empty and has been removed</p>');
            return redirect()->route('reservations');
        }
        $request->session()->flash('message', '<p>Product removed from reservation</p>');
        return redirect()->back();
    }
}
