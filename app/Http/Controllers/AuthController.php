<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        $data = [
            'include_view' => 'snippets.auth_login',
        ];
        return view('templates.development')->with('data', $data);
    }

    public function showAccount() {
        $data = [
            'include_view' => 'snippets.auth_account',
        ];
        return view('templates.development')->with('data', $data);
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
}
