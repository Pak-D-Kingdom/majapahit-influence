<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SetPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicRegistrationController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
<<<<<<< HEAD
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

// Notifications (Dev 5)
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');

    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');
});
=======
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
>>>>>>> origin/chanan
