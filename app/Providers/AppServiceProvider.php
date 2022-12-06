<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
                    $total += $count;
                }
            }
            $view->with('basket_total', $total);    
        });
        //
        // DB::listen(function ($query) {
            // echo '<p>' . $query->sql . ' (' . \implode(' _ ', $query->bindings) . ')</p>';
            // $query->bindings;
            // $query->time;
        // });
    }
}
