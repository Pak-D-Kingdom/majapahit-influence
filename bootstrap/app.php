<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
<<<<<<< HEAD
        then: function () {
=======
        then: function (): void {
>>>>>>> origin/chanan
            Route::middleware('web')
                ->prefix('superadmin')
                ->name('superadmin.')
                ->group(base_path('routes/superadmin.php'));

            Route::middleware('web')
<<<<<<< HEAD
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/superadmin.php'));

            Route::middleware('web')
                ->prefix('kol')
                ->name('kol.')
                ->group(base_path('routes/kol.php'));
        }
=======
                ->prefix('kol')
                ->name('kol.')
                ->group(base_path('routes/kol.php'));
        },
>>>>>>> origin/chanan
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
<<<<<<< HEAD

        $middleware->validateCsrfTokens(except: [
            '/daftar',
            'superadmin/*',
            'admin/*',
            'kol/*',
        ]);
=======
>>>>>>> origin/chanan
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
