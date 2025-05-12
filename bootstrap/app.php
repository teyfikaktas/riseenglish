<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Middleware\TrustProxies as TrustProxiesMiddleware;

return Application::configure(
    basePath: dirname(__DIR__),
)

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    |
    | HTTP (web) ve console komutlarınızı burada bağlayın.
    |
    */
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases & Trusted Proxies
    |--------------------------------------------------------------------------
    |
    | Middleware alias’ları ve proxy ayarları burada tanımlanır.
    | Cloudflare Flexible SSL ile uyumlu çalışması için HEADER_X_FORWARDED_PROTO kullanılır.
    |
    */
    ->withMiddleware(function (Middleware $middleware) {
        // Route middleware alias'ları
        $middleware->alias([
            'role'           => \App\Http\Middleware\EnsureUserHasRole::class,
            'verified.phone' => \App\Http\Middleware\EnsurePhoneVerified::class,
        ]);

        // Laravel 12 önerisiyle Trusted Proxies yapılandırması (Cloudflare uyumlu)
        TrustProxiesMiddleware::trustAllProxies()
            ->withTrustedHeaderNames([
                'forwarded' => Request::HEADER_X_FORWARDED_PROTO,
            ])
            ->applyTo($middleware);
    })

    /*
    |--------------------------------------------------------------------------
    | Exception Rendering
    |--------------------------------------------------------------------------
    |
    | Özel exception handler’larınızı buraya ekleyin.
    |
    */
    ->withExceptions(function (Exceptions $exceptions) {
        // CSRF token eşleşmezse (419 Page Expired)
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oturum süreniz dolmuş. Lütfen sayfayı yenileyip tekrar deneyin.'
                ], 419);
            }

            return redirect('/')
                ->with('warning', 'Oturumunuz zaman aşımına uğradı. Sayfayı yenileyip tekrar deneyin.');
        });

        // Başka özel exception render’ları eklemek isterseniz buraya…
    })

    ->create();
