<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

return Application::configure(
    basePath: dirname(__DIR__),
)

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
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
    */
    ->withMiddleware(function (Middleware $middleware) {
        // Route middleware alias'ları
        $middleware->alias([
            'role'           => \App\Http\Middleware\EnsureUserHasRole::class,
            'verified.phone' => \App\Http\Middleware\EnsurePhoneVerified::class,
        ]);

        // Cloudflare Flexible SSL uyumu için Trusted Proxy ayarı
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_PROTO,
        );
    })

    /*
    |--------------------------------------------------------------------------
    | Exception Rendering
    |--------------------------------------------------------------------------
    */
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oturum süreniz dolmuş. Lütfen sayfayı yenileyip tekrar deneyin.',
                ], 419);
            }

            return redirect('/')
                ->with('warning', 'Oturumunuz zaman aşımına uğradı. Sayfayı yenileyip tekrar deneyin.');
        });
    })

    ->create();
