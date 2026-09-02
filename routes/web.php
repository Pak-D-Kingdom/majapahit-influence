<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});

use App\Http\Controllers\PublicRegistrationController;

Route::get('/daftar', [PublicRegistrationController::class, 'create'])->name('public.kol.register');
Route::post('/daftar', [PublicRegistrationController::class, 'store'])->name('public.kol.store');
Route::get('/daftar/konfirmasi', [PublicRegistrationController::class, 'confirmation'])->name('public.kol.confirmation');