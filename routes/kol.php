<?php

use App\Http\Controllers\Kol\KolEndorsementController;
use Illuminate\Support\Facades\Route;

// KOL Endorsements & Proof Upload
Route::get('endorsements', [KolEndorsementController::class, 'index'])->name('endorsements.index');
Route::get('endorsements/{endorsement}', [KolEndorsementController::class, 'show'])->name('endorsements.show');
Route::post('endorsements/{endorsement}/upload-proof', [KolEndorsementController::class, 'submitProof'])->name('endorsements.upload-proof');
