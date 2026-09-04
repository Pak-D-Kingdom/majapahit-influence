<?php

use App\Http\Controllers\Admin\KolManagementController;
use App\Http\Controllers\Admin\RegistrationReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    // Note: 'role:superadmin' middleware will be created by Dev 1. For now, we just use auth.
    
    // Pendaftaran
    Route::get('/pendaftaran', [RegistrationReviewController::class, 'index'])->name('registrations.index');
    Route::get('/pendaftaran/{registration}', [RegistrationReviewController::class, 'show'])->name('registrations.show');
    Route::post('/pendaftaran/{registration}/approve', [RegistrationReviewController::class, 'approve'])->name('registrations.approve');
    Route::post('/pendaftaran/{registration}/reject', [RegistrationReviewController::class, 'reject'])->name('registrations.reject');

    // Manajemen KOL
    Route::get('/kol/export', [KolManagementController::class, 'export'])->name('kol.export');
    Route::resource('/kol', KolManagementController::class)->except(['destroy']);
    Route::patch('/kol/{kol}/status', [KolManagementController::class, 'updateStatus'])->name('kol.update-status');
});
