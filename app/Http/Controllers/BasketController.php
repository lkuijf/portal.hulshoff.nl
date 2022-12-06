<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class BasketController extends Controller
{
    public function showBasket(Request $request) {
        $basketData = [];
        if($request->session()->has('basket')) { // $currentBasket = \Session::get('basket');
            foreach(session('basket') as $id => $count) {
                $data = [];
                $product = Product::find($id);
                $data['product'] = $product;
                $data['count'] = $count;
                $basketData[] = $data;
            }
        }
        return view('basket')->with('basket', $basketData);
    }

    public function addToBasket(Request $request) {
        $toValidate = array(
            'aantal' => 'required|numeric',
        );
        $validationMessages = array(
            'aantal.required'=> 'Please fill in a value',
            'aantal.numeric'=> 'Only a number is allowed',
        );
        $validated = $request->validate($toValidate,$validationMessages);

        $basket = [];
        if($request->session()->has('basket')) {
            $basket = session('basket');
        }
        $basket[$request->id] = $request->aantal;
        session(['basket' => $basket]);

        $request->session()->flash('message', '<p>Product added to basket</p>');
        return redirect()->back();

        // return redirect()->back()->with('message', 'Product added to basket');
    }

    public function deleteBasketItem(Request $request) {
        $basket = session('basket');
        unset($basket[$request->id]);
        session(['basket' => $basket]);
        $request->session()->flash('message', '<p>Product removed from basket</p>');
        return redirect()->back();
    }

}
