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
    | Middleware Aliases
    |--------------------------------------------------------------------------
    |
    | `routeMiddleware` alias’larınızı buraya ekleyin.
    | Core “web”/“api” grupları otomatik olarak, vendor’dan geliyor.
    |
    */
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'           => \App\Http\Middleware\EnsureUserHasRole::class,
            'verified.phone' => \App\Http\Middleware\EnsurePhoneVerified::class,
        ]);
    })

    /*
    |--------------------------------------------------------------------------
    | Exception Rendering
    |--------------------------------------------------------------------------
    |
    | Buraya özel exception handler’larınızı koyabilirsiniz.
    | Aşağıda CSRF TokenMismatchException için tek bir örnek var.
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
