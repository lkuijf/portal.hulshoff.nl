<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use App\Models\Customer;

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
        view()->composer('*', function ($view) {
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
