<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
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
        // Sadece view'ları tanımlıyoruz, işlevselliği kendi controller'ımızda yapacağız
        Fortify::registerView(function () {
            return view('auth.register');
        });
    
        Fortify::loginView(function () {
            return view('auth.login');
        });
    
        // Diğer view tanımlamaları kalabilir
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });
    
        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });
        
    }
}