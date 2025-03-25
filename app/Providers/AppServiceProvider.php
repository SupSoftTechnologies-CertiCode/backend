<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
        // $apiUrl = url('/api/email/verify/' . $notifiable->getKey() . '/' . sha1($notifiable->getEmailForVerification()));
        $verificationUrl = env('FRONTEND_URL') . "/verify-handler?id=" . $notifiable->getKey() . "&hash=" . sha1($notifiable->getEmailForVerification());

        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line('Click the button below to verify your email address.')
            ->action('Verify Email Address', $verificationUrl);
    });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token";
        });
    }
}
