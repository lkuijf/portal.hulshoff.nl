<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderArticle;
use App\Models\Product;
use App\Models\Address;
use App\Models\Customer;
use App\Models\CustomAddress;
use App\Models\HulshoffUserKlantcode;
use App\Models\HulshoffUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPromoted;
use App\Mail\OrderPlaced;
use App\Mail\WerkbonPdf;
use App\Mail\NotifyMinimumStock;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{

    public $productsBelowMinStock = [];

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
// dd($request);
        $toValidate = array(
            // 'address' => 'required',
            'straat' => 'required_if:customAddressCheckbox,on',
            'huisnummer' => 'required_if:customAddressCheckbox,on',
            'postcode' => 'required_if:customAddressCheckbox,on',
            'plaats' => 'required_if:customAddressCheckbox,on',
        );
        $validationMessages = array(
            // 'address.required'=> 'Please select an address',
            'straat.required_if'=> 'Please enter a straat',
            'huisnummer.required_if'=> 'Please enter a housenumber',
            'postcode.required_if'=> 'Please enter a zipp code',
            'plaats.required_if'=> 'Please enter a city',
        );

        if(!isset($request->customAddressCheckbox)) {
            $toValidate['address'] = 'required';
            $validationMessages['address.required'] = 'Please select an address';
        }

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
        $order->save(); // to set the id
        if(!isset($request->customAddressCheckbox)) {
            $order->address_id = $request->address;
            $order->custom_address_id = null;
        } else {
            $order->address_id = null;

            $addressInformation = ''; // 'informatie cannot be null' should be a migration, but this is a quick fix.
            if($request->information) $addressInformation = $request->information;

            $cAddr = new CustomAddress;
            $cAddr->order_id = $order->id;
            $cAddr->straat = $request->straat;
            $cAddr->huisnummer = $request->huisnummer;
            $cAddr->postcode = $request->postcode;
            $cAddr->plaats = $request->plaats;
            $cAddr->contactpersoon = $request->contactpersoon;
            $cAddr->telefoon = $request->telefoon;
            $cAddr->po_number = $request->po_number;
            $cAddr->informatie = $addressInformation; 
            $cAddr->save();

            $order->custom_address_id = $cAddr->id;
        }
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
                // if($product->isBelowMinStock()) $this->productsBelowMinStock[] = $product->id;
            }
            // if(count($this->productsBelowMinStock)) {
            //     $this->notifyUsersForMinimumStock();
            // }
            $this->generateWerkbon($order);
        }

        foreach($basket as $id => $count) {
            $product = Product::find($id);
            if($product->isBelowMinStock()) $this->productsBelowMinStock[] = $product->id;
        }
        if(count($this->productsBelowMinStock)) {
            $this->notifyUsersForMinimumStock();
        }

        Mail::to(auth()->user()->email)->send(new OrderPlaced($order));
        $extraEmails = json_decode(auth()->user()->extra_email);
        if($extraEmails && count($extraEmails)) {
            foreach($extraEmails as $e_email) {
                Mail::to($e_email)->send(new OrderPlaced($order));
            }
        }

        //copy of confirmation to hulshoff users
        // foreach(config('hulshoff.copy_of_order_confirmation') as $copyEmailAddress) {
        //     Mail::to($copyEmailAddress)->send(new OrderPlaced($order));
        // }

        $request->session()->flash('message', $orderMsg);
        return redirect()->route($redirect);
    }

    public function notifyUsersForMinimumStock() {
        $hhUsersToNotify = [];
        $productsToDisplay = [];
        foreach($this->productsBelowMinStock as $productId) {
            $product = Product::find($productId);
            $productsToDisplay[] = $product;
            $hulshoffUserKlantcodes = HulshoffUserKlantcode::where('klantCode', $product->klantCode)->get();
            foreach($hulshoffUserKlantcodes as $hhuKlantcodes) {
                $hhUser = HulshoffUser::find($hhuKlantcodes->hulshoff_user_id);
                if($hhUser->notify_min_stock) $hhUsersToNotify[] = $hhUser;
            }
        }
        if(count($hhUsersToNotify)) {
            $firstHhUserEmail = array_shift($hhUsersToNotify);
            Mail::to($firstHhUserEmail)->bcc($hhUsersToNotify)->send(new NotifyMinimumStock($productsToDisplay));
        }

        //copy of confirmation to hulshoff users
        foreach(config('hulshoff.copy_of_order_confirmation') as $copyEmailAddress) {
            Mail::to($copyEmailAddress)->send(new NotifyMinimumStock($productsToDisplay));
        }
        
    }

    public function updateOrder(Request $request) {
// dd($request);
        $order = Order::findOr($request->id, function () {
            return abort(404);
        });
        if(($order->hulshoff_user_id != auth()->user()->id) && !auth()->user()->is_admin) return abort(404); // check if order is of the current user when user is not an admin
        if($request->type == 'confirmReservation') {
            $order->is_reservation = 0;
            $order->generateXml();

            $this->updateProductsOrderedValue($order->orderArticles);

            if(count($this->productsBelowMinStock)) {
                $this->notifyUsersForMinimumStock();
            }
        }
        if($request->type == 'updateDeliveryDate') {
            $order->afleverDatum = date("Ymd", strtotime($request->deliveryDate));
        }
        if($request->type == 'updateDeliveryAddress') {
            $order->address_id = $request->address;
            if($request->address && $order->custom_address) {
                $order->custom_address->delete();
            }
        }

        if($request->type == 'updateDeliveryCustomAddress') {
            if(!$order->custom_address) {
                
                $custAddr = new CustomAddress;
                $custAddr->order_id = $order->id;
                if($request->street) $custAddr->straat = $request->street;
                if($request->housenr) $custAddr->huisnummer = $request->housenr;
                if($request->zipp) $custAddr->postcode = $request->zipp;
                if($request->city) $custAddr->plaats = $request->city;
                if($request->person) $custAddr->contactpersoon = $request->person;
                if($request->phone) $custAddr->telefoon = $request->phone;
                if($request->po_number) $custAddr->po_number = $request->po_number;
                if($request->info) $custAddr->informatie = $request->info;
                if($request->street != null || $request->housenr != null || $request->zipp != null || $request->city != null || $request->person != null || $request->phone != null || $request->info != null) {
                    $order->address_id = null;
                    $custAddr->save();
                    $order->custom_address_id = $custAddr->id;
                    $order->save();
                }
            } else {
                $order->custom_address->straat = $request->street;
                $order->custom_address->huisnummer = $request->housenr;
                $order->custom_address->postcode = $request->zipp;
                $order->custom_address->plaats = $request->city;
                $order->custom_address->contactpersoon = $request->person;
                $order->custom_address->telefoon = $request->phone;
                $order->custom_address->po_number = $request->po_number;
                $order->custom_address->informatie = $request->info;
                $order->custom_address->save();
                if($request->street == null && $request->housenr == null && $request->zipp == null && $request->city == null && $request->person == null && $request->phone == null && $request->info == null) {
                    $order->custom_address->delete();
                }
            }
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
            // foreach(config('hulshoff.copy_of_order_confirmation') as $copyEmailAddress) {
            //     Mail::to($copyEmailAddress)->send(new OrderPromoted($order));
            // }



            // $aProds = [];
            // if(count($order->orderArticles)) {
            //     foreach($order->orderArticles as $ordArt) {
            //         $singleProd = new \stdClass();
            //         $product = Product::find($ordArt->product_id);
            //         $singleProd->amount = $ordArt->amount;
            //         $singleProd->artikelCode = $product->artikelCode;
            //         $singleProd->omschrijving = $product->omschrijving;
            //         $singleProd->brand = $product->brand->brand;
            //         $singleProd->group = $product->group->group;
            //         $singleProd->type = $product->type->type;
            //         $aProds[] = $singleProd;
            //     }
            // }
            // if($order->address_id) $werkbonAddress = $order->address;
            // if($order->custom_address_id) $werkbonAddress = $order->custom_address;
            // $pdfData = [
            //     'products' => $aProds,
            //     'order' => $order,
            //     'hulshoffUser' => $order->hulshoffUser,
            //     'customer' => $order->customer,
            //     'address' => $werkbonAddress,
            //     // 'address' => 'mr.',
            // ];

            // $pdf = Pdf::loadView('werkbon.werkbon-pdf', $pdfData);
            // $pdfContent = $pdf->output();

            // //copy of confirmation to hulshoff users
            // foreach(config('hulshoff.copy_of_order_confirmation') as $copyEmailAddress) {
            //     Mail::to($copyEmailAddress)->send(new WerkbonPdf($pdfContent));
            // }

            $this->generateWerkbon($order);


        }

        if($request->type == 'confirmReservation') $request->session()->flash('message', '<p>' . __('Your order has been placed') . '!</p>');
        if($request->type == 'updateDeliveryDate') $request->session()->flash('message', '<p>' . __('Delivery date has been changed') . '</p>');
        if($request->type == 'updateDeliveryAddress') $request->session()->flash('message', '<p>' . __('Delivery address has been changed') . '</p>');
        if($request->type == 'updateDeliveryCustomAddress') $request->session()->flash('message', '<p>' . __('Custom delivery address has been changed') . '</p>');
        if($request->type == 'confirmReservation') return redirect()->route('orders');
        if($request->type == 'updateDeliveryDate') return redirect()->back();
        if($request->type == 'updateDeliveryAddress') return redirect()->back();
        if($request->type == 'updateDeliveryCustomAddress') return redirect()->back();
    }

    public function generateWerkbon($finalOrder) {
        $aProds = [];
        if(count($finalOrder->orderArticles)) {
            foreach($finalOrder->orderArticles as $ordArt) {
                $singleProd = new \stdClass();
                $product = Product::find($ordArt->product_id);
                $singleProd->amount = $ordArt->amount;
                $singleProd->artikelCode = $product->artikelCode;
                $singleProd->omschrijving = $product->omschrijving;
                $singleProd->brand = $product->brand->brand;
                $singleProd->group = $product->group->group;
                $singleProd->type = $product->type->type;
                $aProds[] = $singleProd;
            }
        }
        if($finalOrder->address_id) $werkbonAddress = $finalOrder->address;
        if($finalOrder->custom_address_id) $werkbonAddress = $finalOrder->custom_address;
        $pdfData = [
            'products' => $aProds,
            'order' => $finalOrder,
            'hulshoffUser' => $finalOrder->hulshoffUser,
            'customer' => $finalOrder->customer,
            'address' => $werkbonAddress,
            // 'address' => 'mr.',
        ];

        $pdf = Pdf::loadView('werkbon.werkbon-pdf', $pdfData);
        $pdfContent = $pdf->output();

        //copy of confirmation to hulshoff users
        foreach(config('hulshoff.copy_of_order_confirmation') as $copyEmailAddress) {
            Mail::to($copyEmailAddress)->send(new WerkbonPdf($pdfContent));
        }

    }

    public function updateProductsOrderedValue($orderArticles) {
        if(count($orderArticles)) {
            foreach($orderArticles as $ordArt) {
                $product = Product::find($ordArt->product_id);
                $product->aantal_besteld_onverwerkt += $ordArt->amount;
                $product->save();
                if($product->isBelowMinStock()) $this->productsBelowMinStock[] = $product->id;
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
