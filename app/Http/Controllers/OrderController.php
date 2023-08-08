<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderArticle;
use App\Models\Product;
use App\Models\Address;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPromoted;
use App\Mail\OrderPlaced;

class OrderController extends Controller
{
    public function showOrders(Request $request, $type) {
        if(!auth()->user()->email_verified_at) return view('no-access');

        $t = 0;
        if($type == 'confirmed') $t = 0;
        if($type == 'reserved') $t = 1;
        $populate_onlyMyOrders = false;
        $populate_searchValue = '';

        $orderQry = Order::where('is_reservation', $t);
        if(!auth()->user()->is_admin || (auth()->user()->is_admin && isset($request->showOnlyMyOrders)))
            $orderQry->where('hulshoff_user_id', auth()->user()->id);
        if($request->search) { // when searchfield has been used
            $orderQry->where(function($qry) use($request) {
                $qry->where('id', 'like', '%' . $request->search . '%');
                $qry->orWhere('orderCodeKlant', 'like', '%' . $request->search . '%');
            });
            $populate_searchValue = $request->search;
        }
        $orders = $orderQry->get();

        if(isset($request->showOnlyMyOrders)) $populate_onlyMyOrders = true;

        $data = [
            'orders' => $orders,
            'type' => $type,
            'search_value' => $populate_searchValue,
            'show_only_my_orders' => $populate_onlyMyOrders,
        ];
        return view('orderList')->with('data', $data);
    }

    public function showOrder($id, $type) {
        $order = Order::findOr($id, function () {
            return abort(404);
        });
        if(($order->hulshoff_user_id != auth()->user()->id) && !auth()->user()->is_admin) return abort(404); // check if order is of the current user when user is not an admin
        if($order->is_reservation && $type != 'reserved') return abort(404); // reserved or confirmed order
        if(!$order->is_reservation && $type == 'reserved') return abort(404); // reserved or confirmed order
        $addresses = Address::where('klantCode', $order->klantCode)->get();
        return view('order')->with('order', $order)->with('addresses', $addresses);
    }

    public function newOrder(Request $request) {
        $toValidate = array(
            'address' => 'required',
        );
        $validationMessages = array(
            'address.required'=> 'Please select an address',
        );
        $validated = $request->validate($toValidate,$validationMessages);

        $basket = [];
        $deliveryDate = date("d-m-Y", strtotime('next week'));
        $activeClient = false;
        if($request->session()->has('basket')) {
            $basket = session('basket');
            $request->session()->forget('basket');
        }
        if($request->session()->has('deliveryDate')) {
            $deliveryDate = session('deliveryDate');
            $request->session()->forget('deliveryDate');
        }
        if($request->session()->has('selectedClient')) {
            $activeClient = session('selectedClient');
        }
        
        $order = new Order;
        $order->hulshoff_user_id = auth()->user()->id;
        $order->klantCode = $activeClient;
        $order->is_reservation = auth()->user()->can_reserve;
        $order->orderCodeKlant = 'HUL' . date('U') - strtotime('1-1-2022') . $order->id;
        $order->afleverDatum = date("Ymd", strtotime($deliveryDate));
        // $order->afleverTijd = str_pad($request->deliveryHour, 2, '0', STR_PAD_LEFT) . str_pad($request->deliveryMinute, 2, '0', STR_PAD_LEFT);
        $order->afleverTijd = '0000'; // edit 15-12-2022. Delivery TIME can't be selected by customer.
        $order->address_id = $request->address;
        $order->save();

        foreach($basket as $id => $count) {
            $orderItem = new OrderArticle;
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $id;
            $orderItem->amount = $count;
            $orderItem->save();
        }
        
        $orderMsg = '<p>' . __('Your order has been placed') . '</p>';
        $redirect = 'orders';
        if(auth()->user()->can_reserve) {
            $orderMsg = '<p>' . __('Your reservation has been placed') . '</p>';
            $redirect = 'reservations';
        } else {
            $order->generateXml();
            foreach($basket as $id => $count) {
                $product = Product::find($id);
                $product->aantal_besteld_onverwerkt += $count;
                $product->save();
            }
        }
        Mail::to(auth()->user()->email)->send(new OrderPlaced($order));
        $extraEmails = json_decode(auth()->user()->extra_email);
        if($extraEmails && count($extraEmails)) {
            foreach($extraEmails as $e_email) {
                Mail::to($e_email)->send(new OrderPlaced($order));
            }
        }
        $request->session()->flash('message', $orderMsg);
        return redirect()->route($redirect);
    }

    public function updateOrder(Request $request) {
        $order = Order::findOr($request->id, function () {
            return abort(404);
        });
        if(($order->hulshoff_user_id != auth()->user()->id) && !auth()->user()->is_admin) return abort(404); // check if order is of the current user when user is not an admin
        if($request->type == 'confirmReservation') {
            $order->is_reservation = 0;
            $order->generateXml();

            $this->updateProductsOrderedValue($order->orderArticles);

        }
        if($request->type == 'updateDeliveryDate') {
            $order->afleverDatum = date("Ymd", strtotime($request->deliveryDate));
        }
        if($request->type == 'updateDeliveryAddress') {
            $order->address_id = $request->address;
        }
        // if($request->type == 'addToReservation') {
            
        // }
        $order->save();

        if($request->type == 'confirmReservation') {
            //confirmation to user
            Mail::to(auth()->user()->email)->send(new OrderPromoted($order));

            //copy of confirmation to extra emails
            $extraEmails = json_decode(auth()->user()->extra_email);
            if($extraEmails && count($extraEmails)) {
                foreach($extraEmails as $e_email) {
                    Mail::to($e_email)->send(new OrderPromoted($order));
                }
            }

            //copy of confirmation to hulshoff users
            foreach(config('hulshoff.copy_of_order_confirmation') as $copyEmailAddress) {
                Mail::to($copyEmailAddress)->send(new OrderPromoted($order));
            }
        }

        if($request->type == 'confirmReservation') $request->session()->flash('message', '<p>' . __('Your order has been placed') . '!</p>');
        if($request->type == 'updateDeliveryDate') $request->session()->flash('message', '<p>' . __('Delivery date has been changed') . '</p>');
        if($request->type == 'updateDeliveryAddress') $request->session()->flash('message', '<p>' . __('Delivery address has been changed') . '</p>');
        if($request->type == 'confirmReservation') return redirect()->route('orders');
        if($request->type == 'updateDeliveryDate') return redirect()->back();
        if($request->type == 'updateDeliveryAddress') return redirect()->back();
    }

    public function updateProductsOrderedValue($orderArticles) {
        if(count($orderArticles)) {
            foreach($orderArticles as $ordArt) {
                $product = Product::find($ordArt->product_id);
                $product->aantal_besteld_onverwerkt += $ordArt->amount;
                $product->save();
            }
        }
    }

    public function deleteOrder(Request $request) {
        $order = Order::findOr($request->id, function () {
            return abort(404);
        });
        if(($order->hulshoff_user_id != auth()->user()->id) && !auth()->user()->is_admin) return abort(404); // check if order is of the current user when user is not an admin
        $order->delete();
        $request->session()->flash('message', '<p>' . __('Reservation deleted') . '</p>');
        return redirect()->back();
    }

    public function updateOrderArticle(Request $request) {
        $order = Order::findOr($request->o_id, function () {
            return abort(404);
        });
        if(($order->hulshoff_user_id != auth()->user()->id) && !auth()->user()->is_admin) return abort(404); // check if order is of the current user when user is not an admin

        if(!isset($request->type)) {
            $toValidate = array(
                'count' => 'required|numeric',
            );
            $validationMessages = array(
                'count.required'=> 'Please fill in a value',
                'count.numeric'=> 'Only a number is allowed',
            );
            $validated = $request->validate($toValidate,$validationMessages);
        }

        $product = Product::find($request->p_id);

        if(isset($request->type) && $request->type == 'addToReservation') {
            $orderArticle = new OrderArticle;
            $orderArticle->order_id = $order->id;
            $orderArticle->product_id = $product->id;
            $orderArticle->amount = 1;
            $orderArticle->save();
            session()->forget('addingToReservationId');
            $request->session()->flash('message', '<p>' . __('Product added to reservation') . '</p>');
            return redirect()->route('reservation_detail', $order->id);
        } else {
            $orderArticle = OrderArticle::where('order_id', $order->id)->where('product_id', $product->id)->first();
            if($request->count > ($product->availableAmount() + $orderArticle->amount)) { // currently reserved amount for this order must be added to the availableAmount
                return redirect()->back()->withErrors(['Cannot change to ' . $request->count . ', only ' . ($product->availableAmount() + $orderArticle->amount) . ' are available.']);
            }
            $orderArticle->amount = $request->count;
            $orderArticle->save();
            $request->session()->flash('message', '<p>' . __('Reservation updated') . '</p>');
            return redirect()->back();
        }
    }

    public function deleteOrderArticle(Request $request) {
        // OrderArticle::destroy($request->id);
        $ordArt = OrderArticle::find($request->id);
        $ordArt->delete();
        $totalArticles = OrderArticle::where('order_id', $ordArt->order_id)->count();
        if($totalArticles == 0) {
            Order::destroy($ordArt->order_id);
            $request->session()->flash('message', '<p>' . __('Product removed from reservation') . '</p><p>' . __('Reservation is empty and has been removed') . '</p>');
            return redirect()->route('reservations');
        }
        $request->session()->flash('message', '<p>' . __('Product removed from reservation') . '</p>');
        return redirect()->back();
    }
}
