<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use App\Models\Customer;
use App\Models\Manual;

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
            $total = 0;
            if(session()->has('basket')) {
                $basket = \Session::get('basket');
                foreach($basket as $id => $count) {
                    // $total += $count;
                    $total++;
                }
            }
            $view->with('basket_total', $total);


            if(auth()->user()) {
                $klantCodes = auth()->user()->clientCodes;
                $customers = [];
                foreach($klantCodes as $kcode) {
                    $customer = Customer::find($kcode->klantCode);
                    $customers[] = $customer;
                }
                $view->with('customers', $customers);

                $bShowTiles = false;
                $privileges = json_decode(auth()->user()->privileges);
                if($privileges) {
                    if(in_array('show_tiles', $privileges)) $bShowTiles = true;
                }
                $view->with('tilesDisplay', $bShowTiles);
            }
            
            $currentUrl = parse_url(url()->current(), PHP_URL_PATH);
            $manual = Manual::where('url', $currentUrl)->first();
            if($manual) {
                $view->with('page_manual', $manual->text);
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
