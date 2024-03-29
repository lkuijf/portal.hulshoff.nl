<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manual;

class ManualController extends Controller
{
    public function manualsPage() {
        if(!auth()->user()->is_admin || !auth()->user()->email_verified_at) return view('no-access');
        $manuals = Manual::select('*')->orderBy('url', 'asc')->get();
        $data = [
            'manuals' => $manuals,
        ];
        return view('manualList')->with('data', $data);
    }

    public function showManual($id) {
        if(!auth()->user()->is_admin) return view('no-access');
        if($id == -1) { // it is a new manual
            return view('manual');
        }
        $manual = Manual::find($id);
        if(!$manual) return abort(404);
        return view('manual')->with('manual', $manual);
    }

    public function showNewManual() {
        return $this->showManual(-1);
    }

    public function storeManual(Request $request) {
        $toValidate = array(
            'url' => 'required',
            'text' => 'required',
            'text_en' => 'required',
        );
        $validationMessages = array(
            'url.required'=> 'Please fill in an URL',
            'text.required'=> 'Please enter text for the manual',
            'text_en.required'=> 'Please enter English text for the manual',
        );
        $validated = $request->validate($toValidate,$validationMessages);

        $parsedUrl = parse_url($request->url, PHP_URL_PATH);
        if($parsedUrl == '/products') {
            $fragment = parse_url($request->url, PHP_URL_FRAGMENT); // part after #
            if($fragment) $parsedUrl .= '#' . $fragment;
        }

        $duplicateFound = false;
        if(strtolower($request->_method) == 'post') {
            $manual = new Manual;
            if(Manual::where('url', $parsedUrl)->first()) $duplicateFound = true;
        }
        if(strtolower($request->_method) == 'put') {
            $manual = Manual::find($request->id);
            if($parsedUrl != $manual->url) { // Url has changed of an existing record, check for duplicate
                if(Manual::where('url', $parsedUrl)->first()) $duplicateFound = true;
            }
        }
        if($duplicateFound) return redirect()->back()->withErrors(['Url ' . __('already exists')]);

        $manual->url = $parsedUrl;
        $manual->text = $request->text;
        $manual->text_en = $request->text_en;
        $manual->save();

        $request->session()->flash('message', '<p>' . __('Manual') . ' ' . __('saved') . '</p>');

        if(strtolower($request->_method) == 'post') return redirect(route('manual_detail', ['id' => $manual->id]));
        if(strtolower($request->_method) == 'put') return redirect()->back();
    }
    public function deleteManual(Request $request) {
        $manual = Manual::find($request->id);
        $manual->delete();
        $request->session()->flash('message', '<p>' . __('Manual') . ' ' . __('deleted') . '</p>');
        return redirect()->back();
    }
}
