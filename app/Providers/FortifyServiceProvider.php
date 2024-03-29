<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\AuthController;
use Laravel\Fortify\Contracts\LoginResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->instance(LoginResponse::class, new class implements LoginResponse {
        //     public function toResponse($request)
        //     {
        //         return redirect('/wtf');
        //     }
        // });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function () {
            // return view('auth_hulshoff.login');
            return view('auth_login');

        });
        Fortify::twoFactorChallengeView(function () {
            // return view('auth_hulshoff.two-factor-challenge');
            return view('auth_two-factur-challenge');
        });
        // Fortify::confirmPasswordView(function () {
        //     return view('auth_hulshoff.confirm-password');
        // });
        // Fortify::registerView(function () {
        //     return view('auth_hulshoff.register');
        // });
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth_forgot-password');
        });
        Fortify::resetPasswordView(function ($request) {
            return view('auth_reset-password', ['request' => $request]);
        });
        Fortify::verifyEmailView(function () {
            return view('auth_verify-email');
        });
    }
}
