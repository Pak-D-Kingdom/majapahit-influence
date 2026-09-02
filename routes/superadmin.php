<?php

use App\Http\Controllers\Superadmin\BrandController;
use App\Http\Controllers\Superadmin\CampaignController;
use App\Http\Controllers\Superadmin\ContentProofReviewController;
use App\Http\Controllers\Superadmin\EndorsementController;
use Illuminate\Support\Facades\Route;

// Superadmin Brand Management
Route::resource('brands', BrandController::class);

// Superadmin Campaign Management
Route::resource('campaigns', CampaignController::class);

// Superadmin KOL Assignment & Endorsement Management
Route::post('campaigns/{campaign}/endorsements', [EndorsementController::class, 'store'])->name('campaigns.endorsements.store');
Route::patch('endorsements/{endorsement}/status', [EndorsementController::class, 'updateStatus'])->name('endorsements.update-status');
Route::delete('endorsements/{endorsement}', [EndorsementController::class, 'destroy'])->name('endorsements.destroy');

// Superadmin Content Proof Review
Route::get('content-proofs/{proof}', [ContentProofReviewController::class, 'show'])->name('content-proofs.show');
Route::post('content-proofs/{proof}/review', [ContentProofReviewController::class, 'review'])->name('content-proofs.review');
