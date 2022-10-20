<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

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
        //
        // DB::listen(function ($query) {
            // echo '<p>' . $query->sql . ' (' . \implode(' _ ', $query->bindings) . ')</p>';
            // $query->bindings;
            // $query->time;
        // });
    }
}
