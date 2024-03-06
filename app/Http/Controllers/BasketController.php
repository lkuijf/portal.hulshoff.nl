<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class BasketController extends Controller
{
    public function showBasket(Request $request) {
        $reqType = 'order';
        if(isset($request->type)) {
            $reqType = $request->type;
        }
// dd($request->type); // return-order
        $basketData = [];
        $addressesData = [];
        
        if($request->session()->has('selectedClient')) {
            $activeClient = session('selectedClient');
            $client = Customer::find($activeClient);
            $addressesData = $client->addresses;
        }


        if($reqType == 'order') {
            if($request->session()->has('basket')) { // $currentBasket = \Session::get('basket');
                foreach(session('basket') as $id => $count) {
                    $data = [];
                    $product = Product::find($id);
                    $data['product'] = $product;
                    $data['count'] = $count;
                    $basketData[] = $data;
                }
            }
        } elseif($reqType == 'return-order') {
            // $request->type == return-order
            // basket_return_order
            if($request->session()->has('basket_return_order')) {
                foreach(session('basket_return_order') as $product => $count) {
                    $data = [];
                    $data['product'] = $product;
                    $data['count'] = $count;
                    $basketData[] = $data;
                }
                // foreach(session('basket_return_order') as $id => $count) {
                //     $data = [];
                //     $product = Product::find($id);
                //     $data['product'] = $product;
                //     $data['count'] = $count;
                //     $basketData[] = $data;
                // }
            }
        }


        return view('basket')->with('basket', $basketData)->with('addresses', $addressesData)->with('requestType', $reqType);
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

        $product = Product::find($request->id);
        $availableAmount = $product->availableAmount();
        if($request->aantal > $availableAmount) {
            return redirect()->back()->withErrors([__('Cannot add') . ' ' . $request->aantal . ' ' . __('to your basket, only') . ' ' . $availableAmount . ' ' . __('are available') . '.']);
        }

        $basket = [];
        if($request->session()->has('basket')) {
            $basket = session('basket');
        }
        $basket[$request->id] = $request->aantal;
        session(['basket' => $basket]);

        $request->session()->flash('message', '<p>' . __('Product added to basket') . '</p>');
        return redirect()->back();
    }

    public function deleteFromBasket(Request $request) {
        $basket = session('basket');
        unset($basket[$request->id]);
        session(['basket' => $basket]);
        if(count($basket) == 0) { // basket is empty
            $request->session()->forget('deliveryDate'); // forget the delivery date
        }
        $request->session()->flash('message', '<p>' . __('Product removed from basket') . '</p>');
        return redirect()->back();
    }

    public function updateBasket(Request $request) {
        if(isset($request->deliveryDate)) { // update of the delivery date
            $toValidate = array(
                'deliveryDate' => 'required|date_format:d-m-Y',
            );
            $validationMessages = array(
                'deliveryDate.required'=> 'Please fill in the delivery date',
                'deliveryDate.date_format'=> 'Format must be: dd-mm-yyyy',
            );
            $validated = $request->validate($toValidate,$validationMessages);
            session(['deliveryDate' => $request->deliveryDate]);
        } else { // else it is an update of an article amount
            $toValidate = array(
                'count' => 'required|numeric',
            );
            $validationMessages = array(
                'count.required'=> 'Please fill in a value',
                'count.numeric'=> 'Only a number is allowed',
            );
            $validated = $request->validate($toValidate,$validationMessages);
            $product = Product::find($request->id);
            if($request->count > $product->availableAmount()) {
                return redirect()->back()->withErrors(['Cannot change to ' . $request->count . ', only ' . $product->availableAmount() . ' are available.']);
            }
            $basket = session('basket');
            $basket[$request->id] = $request->count;
            session(['basket' => $basket]);
        }
        $request->session()->flash('message', '<p>' . __('Basket updated') . '</p>');
        return redirect()->back();
    }

    public function resetClientBasket(Request $request) {
        session(['basket' => []]);
        if($request->newClientCode) session(['selectedClient' => $request->newClientCode]);
        else session()->forget('selectedClient');
        if($request->addProductToReservation) session(['addingToReservationId' => $request->addProductToReservation]);
        else session()->forget('addingToReservationId');
        $res = new \stdClass();
        $res->success = true;
        echo json_encode($res);
    }

    public function saveBasketAddress(Request $request) {
        session(['basket_address' => $request->address_info]);
        $res = new \stdClass();
        $res->success = true;
        echo json_encode($res);
    }

    public function obtainBasketAddress() {
        $address = session('basket_address');
        $res = new \stdClass();
        $res->address = $address;
        $res->success = true;
        echo json_encode($res);
    }

}
