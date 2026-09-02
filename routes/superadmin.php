<?php

use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\KolController;
use App\Http\Controllers\Superadmin\RegistrationController;
use App\Http\Controllers\Superadmin\CampaignController;
use App\Http\Controllers\Superadmin\BrandController;
use App\Http\Controllers\Superadmin\EndorsementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Superadmin\AuditTrailController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:superadmin'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('kol', KolController::class)->except(['destroy'])->parameters(['kol' => 'kol']);
    Route::get('/pendaftaran', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/pendaftaran/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::post('/pendaftaran/{registration}/review', [RegistrationController::class, 'review'])->name('registrations.review');
    Route::resource('campaigns', CampaignController::class)->except(['destroy'])->parameters(['campaigns' => 'campaign']);
    Route::post('/campaigns/{campaign}/assign', [CampaignController::class, 'assign'])->name('campaigns.assign');
    Route::resource('brands', BrandController::class)->except(['destroy']);
    Route::resource('endorsements', EndorsementController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('/endorsements/{endorsement}/proof/{proof}/review', [EndorsementController::class, 'reviewProof'])->name('endorsements.proof.review');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('audit.index');
});
