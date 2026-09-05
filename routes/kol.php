<?php

use App\Http\Controllers\Kol\CommissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| KOL (Influencer) Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('kol.dashboard');
})->name('dashboard');

// KOL Commissions
Route::get('commissions', [CommissionController::class, 'index'])->name('commissions.index');
Route::post('commissions/request', [CommissionController::class, 'requestDisbursement'])->name('commissions.request');
