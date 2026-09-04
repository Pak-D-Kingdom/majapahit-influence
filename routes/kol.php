<?php

use App\Http\Controllers\Kol\EndorsementController;
use Illuminate\Support\Facades\Route;

// KOL Endorsements & Proof Upload
Route::get('endorsements', [EndorsementController::class, 'index'])->name('endorsements.index');
Route::get('endorsements/{endorsement}', [EndorsementController::class, 'show'])->name('endorsements.show');
Route::post('endorsements/{endorsement}/upload-proof', [EndorsementController::class, 'uploadProof'])->name('endorsements.upload');
Route::post('endorsements/{endorsement}/upload', [EndorsementController::class, 'uploadProof'])->name('endorsements.upload-alias');
