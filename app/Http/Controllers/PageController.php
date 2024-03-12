<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class PageController extends Controller
{
    public function showHomepage() {

        // $currentOTP = app(Google2FA::class)->getCurrentOtp(decrypt(auth()->user()->two_factor_secret));
        // die($currentOTP);
        return view('home');
    }
}
