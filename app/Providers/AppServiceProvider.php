<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        View::composer(['superadmin.layouts.navbar', 'kol.layouts.navbar'], function ($view): void {
            $view->with('unreadNotificationCount', auth()->check() ? auth()->user()->notifications()->where('is_read', false)->count() : 0);
        });
    }
}
