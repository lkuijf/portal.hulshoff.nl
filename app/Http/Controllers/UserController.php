<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\HulshoffUser;
use App\Models\Customer;

class UserController extends Controller
{
    public function showUsers() {
        $users = HulshoffUser::where('is_admin', 0)->get();

        return view('userList')->with('data', $users);
    }

    public function showUser($id) {
        $user = HulshoffUser::find($id);
        $customers = Customer::get(['klantCode', 'naam']);
        return view('user')->with('data', ['user' => $user, 'customers' => $customers]);
    }

    public function newUser() {
        $customers = Customer::get(['klantCode', 'naam']);
        return view('user')->with('data', ['user' => false, 'customers' => $customers]);
    }

    public function addUser(Request $request) {
        $validated = $this->validateUserData($request);
        $customer = Customer::find($request->klantCode);
        $user = new HulshoffUser;
        $user = $this->populateUserModel($user, $customer, $request);
        $user->save();
        return redirect()->route('users');
    }

    public function updateUser(Request $request) {
        $validated = $this->validateUserData($request);
        $customer = Customer::find($request->klantCode);
        $user = HulshoffUser::find($request->id);
        $user = $this->populateUserModel($user, $customer, $request);
        $user->save();
        return redirect()->back();
    }

    public function validateUserData($req) {
        $toValidate = array(
            'email' => 'required|email',
        );
        $validationMessages = array(
            'email.required'=> 'Geef a.u.b. een e-mail adres op.',
            'email.email'=> 'Het e-mail adres is niet juist geformuleerd.',
        );
        $validated = $req->validate($toValidate,$validationMessages);
        return $validated;
    }

    public function populateUserModel($u, $c, $r) {
        $u->name = $r->name;
        $u->email = $r->email;
        $u->klantCode = $r->klantCode;
        $u->last_known_klantCode_name = $r->klantCode . ',' . $c->naam;
        if($r->privileges) $u->privileges = json_encode($r->privileges);
        return $u;
    }

}
