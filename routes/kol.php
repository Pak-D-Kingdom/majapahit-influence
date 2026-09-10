<?php

use App\Http\Controllers\Kol\CommissionController;
use App\Http\Controllers\Kol\ContentProofController;
use App\Http\Controllers\Kol\DashboardController;
use App\Http\Controllers\Kol\EndorsementController;
use App\Http\Controllers\Kol\LeaderboardController;
use App\Http\Controllers\Kol\ProfileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| KOL Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:kol'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // KOL Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

    // KOL Profile Management (support /profil and /profile)
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show.alias');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit.alias');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update.alias');

    // KOL Endorsements & Proof Upload
    Route::get('/endorsements', [EndorsementController::class, 'index'])->name('endorsements.index');
    Route::get('/endorsements/{endorsement}', [EndorsementController::class, 'show'])->name('endorsements.show');
    Route::post('/endorsements/{endorsement}/upload-proof', [EndorsementController::class, 'uploadProof'])->name('endorsements.upload');
    Route::post('/endorsements/{endorsement}/upload', [EndorsementController::class, 'uploadProof'])->name('endorsements.upload-alias');
    if (class_exists(ContentProofController::class)) {
        Route::get('/endorsements/{endorsement}/proof', [ContentProofController::class, 'create'])->name('endorsements.proof.create');
        Route::post('/endorsements/{endorsement}/proof', [ContentProofController::class, 'store'])->name('endorsements.proof.store');
    }

    // KOL Commissions
    Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
    Route::get('/commissions/{commission}', [CommissionController::class, 'show'])->name('commissions.show');
    Route::post('/commissions/request', [CommissionController::class, 'requestDisbursement'])->name('commissions.request');
    Route::post('/commissions/{commission}/request-disbursement', [CommissionController::class, 'requestDisbursement'])->name('commissions.request-disbursement');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::match(['GET', 'POST', 'PATCH'], '/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});
