<?php

use App\Http\Controllers\Kol\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('kol')->name('kol.')->group(function () {
    // Note: 'role:kol' middleware will be created by Dev 1. For now, we just use auth.
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});
