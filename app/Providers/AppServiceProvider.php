<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        Gate::before(function (User $user, string $ability): ?bool {
            return $user->hasRole('superadmin') || $user->hasRole('admin') ? true : null;
        });

        View::composer(['superadmin.layouts.navbar', 'kol.layouts.navbar', 'superadmin/layouts/navbar', 'kol/layouts/navbar'], function ($view): void {
            $view->with('unreadNotificationCount', auth()->check() ? auth()->user()->notifications()->where('is_read', false)->count() : 0);
        });
    }
}
