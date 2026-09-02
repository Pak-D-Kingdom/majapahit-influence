<?php

use App\Http\Controllers\Kol\DashboardController;
use App\Http\Controllers\Kol\EndorsementController;
use App\Http\Controllers\Kol\ContentProofController;
use App\Http\Controllers\Kol\CommissionController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:kol'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/endorsements', [EndorsementController::class, 'index'])->name('endorsements.index');
    Route::get('/endorsements/{endorsement}', [EndorsementController::class, 'show'])->name('endorsements.show');
    Route::get('/endorsements/{endorsement}/proof', [ContentProofController::class, 'create'])->name('endorsements.proof.create');
    Route::post('/endorsements/{endorsement}/proof', [ContentProofController::class, 'store'])->name('endorsements.proof.store');
    Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
    Route::get('/commissions/{commission}', [CommissionController::class, 'show'])->name('commissions.show');
    Route::post('/commissions/{commission}/request-disbursement', [CommissionController::class, 'requestDisbursement'])->name('commissions.request-disbursement');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
});
