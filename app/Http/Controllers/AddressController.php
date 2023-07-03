<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Customer;

class AddressController extends Controller
{
    public function AddressesPage() {
        if(!auth()->user()->is_admin || !auth()->user()->email_verified_at) return view('no-access');
        $addresses = Address::all();
        $data = [
            'addresses' => $addresses,
        ];
        return view('addressList')->with('data', $data);
    }

    public function showAddress($id) {
        if(!auth()->user()->is_admin) return view('no-access');
        $customers = Customer::get(['klantCode', 'naam']);
        if($id == -1) { // it is a new address
            return view('address')->with('data', ['customers' => $customers]);
        }
        $address = Address::find($id);
        if(!$address) return abort(404);
        return view('address')->with('data', ['address' => $address,'customers' => $customers]);
    }

    public function showNewAddress() {
        return $this->showAddress(-1);
    }

    public function storeAddress(Request $request) {
        $toValidate = array(
            'naam' => 'required',
            'klantCode' => 'required',
            'straat' => 'required',
            'huisnummer' => 'required',
            'postcode' => 'required',
            'plaats' => 'required',
            'landCode' => 'required',
            'contactpersoon' => 'required',
            'telefoon' => 'required',
            'eMailadres' => 'required|email',
        );
        $validationMessages = array(
            'naam.required'=> 'Please fill in a name',
            'klantCode.required'=> 'Please select a client',
            'straat.required'=> 'Please fill in a street',
            'huisnummer.required'=> 'Please fill in a house number',
            'postcode.required'=> 'Please fill in a zipp code',
            'plaats.required'=> 'Please fill in a city',
            'landCode.required'=> 'Please fill in a country code',
            'contactpersoon.required'=> 'Please fill in a contact person',
            'telefoon.required'=> 'Please fill in a phone number',
            'eMailadres.required'=> 'Please fill in an e-mail adres',
            'eMailadres.email'=> 'The e-mail address is not correctly formulated',
        );
        $validated = $request->validate($toValidate,$validationMessages);

        if(strtolower($request->_method) == 'post') $address = new Address;
        if(strtolower($request->_method) == 'put') $address = Address::find($request->id);

        $address->naam = $request->naam;
        $address->klantCode = $request->klantCode;
        $address->straat = $request->straat;
        $address->huisnummer = $request->huisnummer;
        $address->postcode = $request->postcode;
        $address->plaats = $request->plaats;
        $address->landCode = $request->landCode;
        $address->contactpersoon = $request->contactpersoon;
        $address->telefoon = $request->telefoon;
        $address->eMailadres = $request->eMailadres;
        $address->save();

        $request->session()->flash('message', '<p>' . __('Address') . ' ' . __('saved') . '</p>');

        if(strtolower($request->_method) == 'post') return redirect(route('address_detail', ['id' => $address->id]));
        if(strtolower($request->_method) == 'put') return redirect()->back();
    }
    public function deleteAddress(Request $request) {
        $address = Address::find($request->id);
        $address->delete();
        $request->session()->flash('message', '<p>' . __('Address') . ' ' . __('deleted') . '</p>');
        return redirect()->back();
    }
}
