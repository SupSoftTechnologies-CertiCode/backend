<?php

use App\Http\Controllers\Auth\SocialAuthenticationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::controller(SocialAuthenticationController::class)->group(function () {
    Route::get('auth/redirection/{provider}', 'authProvideRedirection');
    Route::get('auth/{provider}/callback', 'socialAuthentication');
});

require __DIR__.'/auth.php';
