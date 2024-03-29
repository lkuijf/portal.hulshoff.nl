<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use App\Models\Customer;
use App\Models\Manual;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('templates.portal', function ($view) {

            // \Session::put('fortify.2fa_disabled', true);
            // \Session::put('status', 'two-factor-authentication-confirmed');

            // if(auth()->user()) {
            //     // auth()->user()->forceFill([
            //     //     'two_factor_secret' => null,
            //     //     'two_factor_recovery_codes' => null,
            //     // ])->save();

            //     dd(auth()->user());

            // }



            $total = 0;
            if(session()->has('basket')) {
                $basket = \Session::get('basket');
                foreach($basket as $id => $count) {
                    $total++;
                }
            }
            $view->with('basket_total', $total);


            $totalReturn = 0;
            if(session()->has('basket_return_order')) {
                $basketRet = \Session::get('basket_return_order');
                foreach($basketRet as $id => $count) {
                    $totalReturn++;
                }
            }
            $view->with('return_basket_total', $totalReturn);


            if(auth()->user()) {
                $klantCodes = auth()->user()->clientCodes;
                $customers = [];
                foreach($klantCodes as $kcode) {
                    $customer = Customer::find($kcode->klantCode);
                    $customers[$kcode->klantCode] = $customer->naam;
                }
                asort($customers);
                $view->with('customers', $customers);

                $bShowTiles = false;
                $privileges = json_decode(auth()->user()->privileges);
                if($privileges) {
                    if(in_array('show_tiles', $privileges)) $bShowTiles = true;
                }
                $view->with('tilesDisplay', $bShowTiles);
            }
            
            $currentUrl = parse_url(url()->current(), PHP_URL_PATH);
            $fragment = parse_url(url()->current(), PHP_URL_FRAGMENT); // part after #
            if($fragment) $currentUrl .= '#' . $fragment;

            // $manual = Manual::where('url', $currentUrl)->first();
            // dd('test');
            if(!$currentUrl) { // the root has been called
                $manuals = Manual::where('url', '/')->get();
// dd($manuals);
                $view->with('isHomepage', true);
            } else {
                $manuals = Manual::where('url', 'like', $currentUrl . '%')->get();
            }
// dd($manuals);
            if($manuals) {
                $view->with('page_manuals', $manuals);
            }
            

        });
        //
        // DB::listen(function ($query) {
        //     echo '<p>' . $query->sql . ' (' . \implode(' _ ', $query->bindings) . ')</p>';
        //     $query->bindings;
        //     $query->time;
        // });
    }
}
