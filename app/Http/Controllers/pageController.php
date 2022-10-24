<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pageController extends Controller
{
    public function pageXml() {
        $data = ['content' => 'content'];
        return view('welcome_hulshoff')->with('data', $data);
    }
}
