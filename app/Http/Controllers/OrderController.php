<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderArticle;

class OrderController extends Controller
{
    public function showOrders() {
        $orders = Order::where('hulshoff_user_id', auth()->user()->id)->get();
        $data = [
            'orders' => $orders,
            // 'type' => 'users',
        ];
        return view('orderList')->with('data', $data);
    }
    public function newOrder(Request $request) {
        $toValidate = array(
            'deliveryDate' => 'required|date_format:d-m-Y',
            'deliveryHour' => 'required',
            'deliveryMinute' => 'required',
        );
        $validationMessages = array(
            'deliveryDate.required'=> 'Please fill in the delivery date',
            'deliveryDate.date_format'=> 'Date format must be: dd-mm-yyyy',
            'deliveryHour.required'=> 'Please fill in the delivery hour',
            'deliveryMinute.required'=> 'Please fill in the delivery minute',
        );
        $validated = $request->validate($toValidate,$validationMessages);

        $order = new Order;
        $order->hulshoff_user_id = auth()->user()->id;
        $order->aleverenDatum = date("Ymd", strtotime($request->deliveryDate));
        $order->aleverenTijd = str_pad($request->deliveryHour, 2, '0', STR_PAD_LEFT) . str_pad($request->deliveryMinute, 2, '0', STR_PAD_LEFT);
        $order->save();

        return redirect()->route('orders');
    }
}
