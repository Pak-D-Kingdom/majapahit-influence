<?php

use App\Http\Controllers\Kol\CommissionController;
use App\Http\Controllers\Kol\EndorsementController;
use App\Http\Controllers\Kol\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| KOL Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    if (view()->exists('kol.dashboard')) {
        return view('kol.dashboard');
    }
    return response()->json([
        'message' => 'KOL Dashboard',
        'user' => auth()->user(),
    ]);
})->name('dashboard');

// Dev 2: KOL Profile Management
Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

// Dev 3: KOL Endorsements & Proof Upload
Route::get('endorsements', [EndorsementController::class, 'index'])->name('endorsements.index');
Route::get('endorsements/{endorsement}', [EndorsementController::class, 'show'])->name('endorsements.show');
Route::post('endorsements/{endorsement}/upload-proof', [EndorsementController::class, 'uploadProof'])->name('endorsements.upload');
Route::post('endorsements/{endorsement}/upload', [EndorsementController::class, 'uploadProof'])->name('endorsements.upload-alias');

// Dev 4: KOL Commissions
Route::get('commissions', [CommissionController::class, 'index'])->name('commissions.index');
Route::post('commissions/request', [CommissionController::class, 'requestDisbursement'])->name('commissions.request');
