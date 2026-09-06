<?php

use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\EndorsementController;
use App\Http\Controllers\Admin\KolManagementController;
use App\Http\Controllers\Admin\RegistrationReviewController;
use App\Http\Controllers\Superadmin\BrandController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Superadmin & Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return response()->json([
        'message' => 'Admin Dashboard',
        'user' => auth()->user(),
    ]);
})->name('dashboard');

// Dev 2: Pendaftaran Review
Route::get('/pendaftaran', [RegistrationReviewController::class, 'index'])->name('registrations.index');
Route::get('/pendaftaran/{registration}', [RegistrationReviewController::class, 'show'])->name('registrations.show');
Route::post('/pendaftaran/{registration}/approve', [RegistrationReviewController::class, 'approve'])->name('registrations.approve');
Route::post('/pendaftaran/{registration}/reject', [RegistrationReviewController::class, 'reject'])->name('registrations.reject');

// Dev 2: Manajemen KOL
Route::get('/kol/export', [KolManagementController::class, 'export'])->name('kol.export');
Route::resource('/kol', KolManagementController::class)->except(['destroy']);
Route::patch('/kol/{kol}/status', [KolManagementController::class, 'updateStatus'])->name('kol.update-status');

// Dev 3: Superadmin Brand Management
Route::resource('brands', BrandController::class);

// Dev 3: Superadmin Campaign Management
Route::resource('campaigns', CampaignController::class);

// Dev 3: Superadmin KOL Assignment & Endorsement Lifecycle
Route::post('campaigns/{campaign}/assign', [EndorsementController::class, 'assign'])->name('campaigns.assign');
Route::post('campaigns/{campaign}/endorsements', [EndorsementController::class, 'assign'])->name('campaigns.endorsements.store');
Route::post('endorsements/{endorsement}/review', [EndorsementController::class, 'reviewProof'])->name('endorsements.review');
Route::post('endorsements/{endorsement}/complete', [EndorsementController::class, 'complete'])->name('endorsements.complete');
Route::delete('endorsements/{endorsement}', [EndorsementController::class, 'destroy'])->name('endorsements.destroy');
