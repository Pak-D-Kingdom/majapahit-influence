<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('landing.index');
});


/*
|--------------------------------------------------------------------------
| Superadmin
|--------------------------------------------------------------------------
*/

Route::prefix('superadmin')->name('superadmin.')->group(function () {

    // ============================================================
    // AUTH / LOGIN
    // ============================================================

    Route::view('/login', 'superadmin.auth.login')
        ->name('login');

        
    // ============================================================
    // LOGOUT
    // ============================================================

    Route::get('/logout', function () {
        return redirect()->route('superadmin.login');
    })->name('logout');


    // ============================================================
    // DASHBOARD
    // ============================================================

    Route::view('/', 'superadmin.dashboard')
        ->name('dashboard');


    // ============================================================
    // KOL
    // ============================================================

    Route::view('/kol', 'superadmin.kol.kol')
        ->name('kol');


    // ============================================================
    // REGISTRATIONS
    // ============================================================

    Route::view('/registrations', 'superadmin.registrations.registration')
        ->name('registrations');


    // ============================================================
    // BRANDS
    // ============================================================

    Route::view('/brands', 'superadmin.brands.brand')
        ->name('brands');


    // ============================================================
    // CAMPAIGNS
    // ============================================================

    Route::view('/campaigns', 'superadmin.campaigns.campaigns')
        ->name('campaigns');


    // ============================================================
    // COMMISSIONS
    // ============================================================

    Route::view('/commissions', 'superadmin.commissions.commission')
        ->name('commissions');


    // ============================================================
    // NOTIFICATIONS
    // ============================================================

    Route::view('/notifications', 'superadmin.notifications.notification')
        ->name('notifications');


    // ============================================================
    // AUDIT TRAIL
    // ============================================================

    Route::view('/audit-trail', 'superadmin.audit-trail.audit-trail')
        ->name('audit-trail');


    // ============================================================
    // USERS
    // ============================================================

    Route::view('/users', 'superadmin.users.user')
        ->name('users');


    // ============================================================
    // SETTINGS
    // ============================================================

    Route::view('/settings', 'superadmin.settings.setting')
        ->name('settings');


    // ============================================================
    // REPORTS
    // ============================================================

    Route::view('/reports', 'superadmin.reports.report')
        ->name('reports');

});