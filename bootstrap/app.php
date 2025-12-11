<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

return Application::configure(
    basePath: dirname(__DIR__),
)

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'              => \App\Http\Middleware\EnsureUserHasRole::class,
            'verified.phone'    => \App\Http\Middleware\EnsurePhoneVerified::class,
            'teacher.approved'  => \App\Http\Middleware\TeacherApproved::class, // ← BURAYI EKLE
        ]);

        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_PROTO,
        );
    })

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