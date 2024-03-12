<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

use App\Models\HulshoffUser;
use App\Notifications\SendOTP;

class AuthController extends Controller
{
    public function showLogin() {
        $data = [
            'include_view' => 'snippets.auth_login',
        ];
        return view('templates.development')->with('data', $data);
    }

    public function showAccount() {
        // $data = [
        //     'include_view' => 'snippets.auth_account',
        // ];
        // return view('templates.development')->with('data', $data);
        return view('auth_account_home');
    }

    public function attemptLogin(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('account');
            // return 'logged in';
        }
 
        return back()->withErrors([
            'not_found' => 'no user found',
        ])->onlyInput('email');
    }

    public function setLanguage(Request $request) {
        $res = new \stdClass();

        session(['language' => $request->newLang]);

        $res->success = true;
        echo json_encode($res);
    }

    public function send2FaCodeViaEmail(Request $request) {
        // auth()->user()
        // $event->user->notify(app(SendOTP::class));
        // dd(Auth::id());
        // dd($request);
        // dd($request->user());

        // dd(auth()->guard('h_users'));
        // dd(auth());
        // dd(session('login')['id']);
        // $req = $request->session();
        // dd($req);
        // dd($request);
        // dd($request->user()->id);
        // dd(auth()->user());

        // dd($user);

        //$event->user->notify(app(SendOTP::class));
        $user = HulshoffUser::find(session('login')['id']);
        // echo $user->email;
        $user->notify(app(SendOTP::class));
        echo 'Code send';

    }

}
