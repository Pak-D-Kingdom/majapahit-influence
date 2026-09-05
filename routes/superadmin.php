<?php

use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Superadmin Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('superadmin.dashboard');
})->name('dashboard');

// Commission Management
Route::get('commissions/export', [CommissionController::class, 'export'])->name('commissions.export');
Route::get('commissions', [CommissionController::class, 'index'])->name('commissions.index');
Route::get('commissions/{commission}', [CommissionController::class, 'show'])->name('commissions.show');
Route::post('commissions/approve', [CommissionController::class, 'approve'])->name('commissions.approve');
Route::post('commissions/{commission}/process', [CommissionController::class, 'process'])->name('commissions.process');

// Reports
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/commissions/export', [ReportController::class, 'exportCommissions'])->name('reports.commissions.export');
Route::get('reports/kol/export', [ReportController::class, 'exportKol'])->name('reports.kol.export');
