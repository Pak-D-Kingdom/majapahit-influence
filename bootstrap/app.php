<?php

use App\Http\Middleware\RoleMiddleware;
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
        then: function (): void {
            Route::middleware(['web', 'auth', 'role:superadmin,admin'])
                ->prefix('superadmin')
                ->name('superadmin.')
                ->group(base_path('routes/superadmin.php'));

            Route::middleware(['web', 'auth', 'role:superadmin,admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/superadmin.php'));

            Route::middleware(['web', 'auth', 'role:kol'])
                ->prefix('kol')
                ->name('kol.')
                ->group(base_path('routes/kol.php'));

            Route::middleware(['web', 'auth', 'role:brand'])
                ->prefix('brand')
                ->name('brand.')
                ->group(base_path('routes/brand.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        $middleware->redirectTo(
            guests: '/login',
            users: function (Request $request) {
                $user = $request->user();
                if ($user?->isAdmin()) {
                    return route('superadmin.dashboard');
                }
                if ($user?->isKol()) {
                    return route('kol.dashboard');
                }
                if ($user?->isBrand()) {
                    return route('brand.dashboard');
                }

                return '/';
            }
        );

        $middleware->validateCsrfTokens(except: [
            '/daftar',
            'superadmin/*',
            'admin/*',
            'kol/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
