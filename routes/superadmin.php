<?php

use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\EndorsementController;
use App\Http\Controllers\Superadmin\BrandController;
use Illuminate\Support\Facades\Route;

// Superadmin Brand Management
Route::resource('brands', BrandController::class);

// Superadmin Campaign Management
Route::resource('campaigns', CampaignController::class);

// Superadmin KOL Assignment & Endorsement Lifecycle
Route::post('campaigns/{campaign}/assign', [EndorsementController::class, 'assign'])->name('campaigns.assign');
Route::post('campaigns/{campaign}/endorsements', [EndorsementController::class, 'assign'])->name('campaigns.endorsements.store');
Route::post('endorsements/{endorsement}/review', [EndorsementController::class, 'reviewProof'])->name('endorsements.review');
Route::post('endorsements/{endorsement}/complete', [EndorsementController::class, 'complete'])->name('endorsements.complete');
Route::delete('endorsements/{endorsement}', [EndorsementController::class, 'destroy'])->name('endorsements.destroy');
