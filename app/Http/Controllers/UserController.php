<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Models\HulshoffUser;
use App\Models\HulshoffUserKlantcode;
use App\Models\Customer;

class UserController extends Controller
{
    public function showUsers(Request $request) {
        if(!auth()->user()->is_admin || !auth()->user()->email_verified_at) return view('no-access');

        $search = false;
        if ($request->has('search')) {
            $search = $request->input('search');
        }

        $isAdmin = 0;
        if(Route::currentRouteName() == 'admins') $isAdmin = 1;
        $usrQry = HulshoffUser::where('is_admin', $isAdmin);
        if($search) {
            $usrQry->where('name', 'like', '%' . $search . '%');
            $usrQry->orWhere('email', 'like', '%' . $search . '%');
            $usrQry->orWhere('extra_email', 'like', '%' . $search . '%');
        }
        $users = $usrQry->get();
        $data = [
            'users' => $users,
            'type' => $isAdmin,
        ];
        return view('userList')->with('data', $data);
    }

    public function showUser($id) {
        if(!auth()->user()->is_admin) return view('no-access');
        $user = HulshoffUser::find($id);
// dd($user->customers);
        $customers = Customer::get(['klantCode', 'naam']);
        $userCustomers = HulshoffUserKlantcode::where('hulshoff_user_id', $id)->get('klantCode');
        return view('user')->with('data', ['user' => $user, 'customers' => $customers, 'userCustomers' => $userCustomers]);
    }

    public function newUser() {
        $customers = Customer::get(['klantCode', 'naam']);
        return view('user')->with('data', ['user' => false, 'customers' => $customers]);
    }

    public function addUser(Request $request) {
        $validated = $this->validateUserData($request);

        if(HulshoffUser::where('email', $request->email)->first()) {
            return redirect()->back()->withErrors(['E-mail address is already in use'])->withInput();
        }

        $user = new HulshoffUser;
        $user = $this->populateUserModel($user, $request);
        $user->save();

        foreach($request->klantCode as $kCode) {
            if($kCode) {
                $hhUserKlant = HulshoffUserKlantcode::firstOrCreate([
                    'hulshoff_user_id' => $user->id,
                    'klantCode' => $kCode
                ]);
            }
        }

        // $user->sendEmailVerificationNotification();
        $token = Password::getRepository()->create($user);
        $user->sendPasswordResetNotification($token);
        // sendPasswordResetNotification
        $request->session()->flash('message', '<p>' . __('Success. The user received a notification (e-mail) to reset their password.') . '</p>');
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
            $validated = $this->validateUserData($request, false);
            $user = $this->populateUserModel($user, $request);
        }
        $user->save();

        HulshoffUserKlantcode::where('hulshoff_user_id', $user->id)->delete();
        foreach($request->klantCode as $kCode) {
            if($kCode) {
                $hhUserKlant = HulshoffUserKlantcode::firstOrCreate([
                    'hulshoff_user_id' => $user->id,
                    'klantCode' => $kCode
                ]);
            }
        }

        $request->session()->flash('message', '<p>' . __('User saved') . '</p>');
        return redirect()->back();
    }

    public function deleteUser(Request $request) {
        HulshoffUser::destroy($request->id);
        return redirect()->back();
    }

    public function validateUserData($req, $bValidateEmail = true) {
        $method = strtolower($req->method());
        $toValidate = array(
            'name' => 'required',
        );
        $validationMessages = array(
            'name.required' => 'Please enter a name',
        );
        if($bValidateEmail) {
            $toValidate['email'] = 'required|email';
            $validationMessages['email.required'] = 'Please enter an e-mail address';
            $validationMessages['email.email'] = 'The e-mail address is not correctly formulated';
        }
        $validated = $req->validate($toValidate,$validationMessages);
        return $validated;
    }

    public function validateExtraEmail($req) {
        $toValidate = array(
            'extra_email' => 'required|email',
        );
        $validationMessages = array(
            'extra_email.required'=> 'Please enter an e-mail address',
            'extra_email.email'=> 'The e-mail address is not correctly formulated',
        );
        $validated = $req->validate($toValidate,$validationMessages);
        return $validated;
    }

    public function populateUserModel($usr, $req) {
        $usr->name = $req->name;
        if(isset($req->email)) $usr->email = $req->email;
        if(isset($req->password)) $usr->password = Hash::make($req->password);

        // $usr->klantCode = $req->klantCode;
        // if(isset($cust->naam)) $usr->last_known_klantCode_name = $req->klantCode . ',' . $cust->naam;

        $usr->privileges = ($req->privileges?json_encode($req->privileges):null);
        $usr->can_reserve = ($req->can_reserve?1:0);
        $usr->is_admin = ($req->is_admin?1:0);
        return $usr;
    }

}
