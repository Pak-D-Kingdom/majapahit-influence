<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'create'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'store'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'store'])->name('password.update');
});

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('/set-password', [\App\Http\Controllers\Auth\SetPasswordController::class, 'create'])->name('password.set');
Route::post('/set-password', [\App\Http\Controllers\Auth\SetPasswordController::class, 'store'])->name('password.set.store');

Route::get('/daftar', [\App\Http\Controllers\RegistrationController::class, 'create'])->name('registration.create');
Route::post('/daftar', [\App\Http\Controllers\RegistrationController::class, 'store'])->name('registration.store');
Route::get('/daftar/konfirmasi/{registration}', [\App\Http\Controllers\RegistrationController::class, 'confirmation'])->name('registration.confirmation');
