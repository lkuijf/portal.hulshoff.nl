<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Models\HulshoffUser;
use App\Models\Customer;

class UserController extends Controller
{
    public function showUsers() {
        if(!auth()->user()->is_admin) return view('no-access');
        $isAdmin = 0;
        if(Route::currentRouteName() == 'admins') $isAdmin = 1;
        $users = HulshoffUser::where('is_admin', $isAdmin)->get();
        $data = [
            'users' => $users,
            'type' => $isAdmin,
        ];
        return view('userList')->with('data', $data);
    }

    public function showUser($id) {
        if(!auth()->user()->is_admin) return view('no-access');
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
        // $user->sendEmailVerificationNotification();
        $token = Password::getRepository()->create($user);
        $user->sendPasswordResetNotification($token);
        // sendPasswordResetNotification
        if($user->is_admin) return redirect()->route('admins');
        return redirect()->route('users');
    }

    public function updateUser(Request $request) {
// dd($request);
        $customer = Customer::find($request->klantCode);
        $user = HulshoffUser::find($request->id);
        $emailToRemove = false;
        if(isset($request->current_extra_emails)) {
            $emailToRemoveKey = -1;
            foreach($request->current_extra_emails as $k => $email) {
                if($email == 'Remove') $emailToRemoveKey = $k - 1; // the previous index is the email to remove
            }
            if($emailToRemoveKey != -1) $emailToRemove = $request->current_extra_emails[$emailToRemoveKey];
        }
        if($emailToRemove) {
            $curEmails = json_decode($user->extra_email);
            $newEmails = [];
            foreach($curEmails as $info) {
                if($info->email != $emailToRemove) {
                    $res = new \stdClass();
                    $res->email = $info->email;
                    $newEmails[] = $res;
                }
            }
            $user->extra_email = json_encode($newEmails);
        } elseif(isset($request->add_email_btn)) {
            $validated = $this->validateExtraEmail($request);
            $curEmails = json_decode($user->extra_email);
            $res = new \stdClass();
            $res->email = $request->extra_email;
            $curEmails[] = $res;
            $user->extra_email = json_encode($curEmails);
        } else {
            $validated = $this->validateUserData($request);
            $user = $this->populateUserModel($user, $customer, $request);
        }
        $user->save();
        $request->session()->flash('message', '<p>User saved</p>');
        return redirect()->back();
    }

    public function deleteUser(Request $request) {
        HulshoffUser::destroy($request->id);
        return redirect()->back();
    }

    public function validateUserData($req) {
        $method = strtolower($req->method());
        $toValidate = array(
            'email' => 'required|email',
            'name' => 'required',
            // 'password' => 'required',
            // 'klantCode' => 'required',
        );
        $validationMessages = array(
            'email.required'=> 'Geef a.u.b. een e-mail adres op.',
            'email.email'=> 'Het e-mail adres is niet juist geformuleerd.',
            'name.required'=> 'Geef a.u.b. een naam op.',
            // 'klantCode.required'=> 'Geef a.u.b. aan bij welke klant de gebruiker hoort.',
        );
        // if($method == 'post') {
        //     $toValidate['password'] = 'required';
        //     $validationMessages['password.required'] = 'Geef a.u.b. een wachtwoord op.';
        // }
        $validated = $req->validate($toValidate,$validationMessages);
        return $validated;
    }

    public function validateExtraEmail($req) {
        $toValidate = array(
            'extra_email' => 'required|email',
        );
        $validationMessages = array(
            'extra_email.required'=> 'Geef a.u.b. een e-mail adres op.',
            'extra_email.email'=> 'Het e-mail adres is niet juist geformuleerd.',
        );
        $validated = $req->validate($toValidate,$validationMessages);
        return $validated;
    }

    public function populateUserModel($usr, $cust, $req) {
        $usr->name = $req->name;
        $usr->email = $req->email;
        if(isset($req->password)) $usr->password = Hash::make($req->password);
        $usr->klantCode = $req->klantCode;
        if(isset($cust->naam)) $usr->last_known_klantCode_name = $req->klantCode . ',' . $cust->naam;
        // else $usr->last_known_klantCode_name = null;
        $usr->privileges = ($req->privileges?json_encode($req->privileges):null);
        $usr->can_reserve = ($req->can_reserve?1:0);
        $usr->is_admin = ($req->is_admin?1:0);
        return $usr;
    }

}
