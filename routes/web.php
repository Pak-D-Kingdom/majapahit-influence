<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SetPasswordController;
use App\Http\Controllers\PublicRegistrationController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    if (view()->exists('landing.index')) {
        return view('landing.index');
    }
    return response()->json(['message' => 'Majapahit Influence API is running']);
})->name('home');

// Public KOL Registration (Dev 2)
Route::get('/daftar', [PublicRegistrationController::class, 'create'])->name('public.register');
Route::post('/daftar', [PublicRegistrationController::class, 'store'])->name('public.register.store');
Route::get('/daftar/konfirmasi', [PublicRegistrationController::class, 'confirmation'])->name('public.register.confirmation');

// Authentication Routes (Guest Only) (Dev 1)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

    // Set Initial Password for Approved KOL
    Route::get('/kol/set-password/{token}', [SetPasswordController::class, 'showSetPasswordForm'])->name('password.set');
    Route::post('/kol/set-password', [SetPasswordController::class, 'updatePassword'])->name('password.set.post');
});

// Logout (Authenticated Only)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
