<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Gerekli using ifadeleri (dosyanın başına ekleyin veya zaten varsa kontrol edin)
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;
// redirect() helper'ı için genelde ek using gerekmez, ama Redirect Facade için:
// use Illuminate\Support\Facades\Redirect;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'verified.phone' => \App\Http\Middleware\EnsurePhoneVerified::class, // Doğru sınıf adını kullanın
            ]);
        })
    ->withExceptions(function (Exceptions $exceptions) { // <-- Burası düzenlendi

        // TokenMismatchException (Page Expired) yakalandığında çalışacak render kuralı
        $exceptions->render(function (TokenMismatchException $e, Request $request) {

            // Eğer istek AJAX ise (API vb.), JSON yanıtı döndürmek daha uygun olabilir
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oturum süresi doldu veya sayfa geçersiz. Lütfen sayfayı yenileyin.'
                ], 419); // 419 Authentication Timeout (CSRF hatası için uygun)
            }

            // Normal web istekleri için doğrudan ana dizine (/) yönlendir
            return redirect('/') // '/' adresine yönlendir
                   ->with('warning', 'Oturumunuz zaman aşımına uğradı veya sayfa çok uzun süre bekledi. Lütfen işlemi tekrar deneyin.'); // Flash mesajı ekle

        });

        // Buraya başka exception handling kuralları eklenebilir...

    })->create();