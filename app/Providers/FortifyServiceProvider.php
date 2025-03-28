<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Events\RegisterResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

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
    
        // Diğer view tanımlamaları
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });
    
        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });
        
        // Telefon doğrulama view'ını ekleyelim
        Fortify::verifyEmailView(function () {
            return view('auth.verify-phone');
        });
        
        // Register sonrası olay dinleme - bu metodu ekleyelim
        Event::listen(Laravel\Fortify\Events\RegisterResponse::class, function ($event) {
            return redirect()->route('verification.phone.notice');
        });
    }
}