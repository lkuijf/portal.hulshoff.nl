<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->greeting(__('Hello') . '!')
                ->subject(__('Verify Email Address'))
                ->line(__('Click the button below to verify your email address') . '.')
                ->action(__('Verify Email Address'), $url)
                ->salutation(new HtmlString(__('Regards') . ',<br>Hulshoff'))
                ;
        });
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->greeting(__('Hello') . '!')
                ->subject(__('Reset Password'))
                ->line(__('You are receiving this email because we received a password reset request for your account') . '.')
                ->action(__('Reset Password'), route('custom.password.reset', $token))
                ->line(__('This password reset link will expire in 60 minutes') . '.')
                ->line(__('If you did not request a password reset, no further action is required') . '.')
                ->salutation(new HtmlString(__('Regards') . ',<br>Hulshoff'))
                ;
        });
    }
}
