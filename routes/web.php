<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SetPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicRegistrationController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    if (view()->exists('landing.index')) {
        return view('landing.index');
    }
    return response()->json(['message' => 'Majapahit Influence API is running']);
})->name('home');

// Public KOL Registration (Dev 2)
if (class_exists(PublicRegistrationController::class)) {
    Route::get('/daftar', [PublicRegistrationController::class, 'create'])->name('public.register');
    Route::post('/daftar', [PublicRegistrationController::class, 'store'])->name('public.register.store');
    Route::get('/daftar/konfirmasi', [PublicRegistrationController::class, 'confirmation'])->name('public.register.confirmation');
} elseif (class_exists(RegistrationController::class)) {
    Route::get('/daftar', [RegistrationController::class, 'create'])->name('public.register');
    Route::post('/daftar', [RegistrationController::class, 'store'])->name('public.register.store');
    Route::get('/daftar/konfirmasi/{registration?}', [RegistrationController::class, 'confirmation'])->name('public.register.confirmation');
}

// Authentication Routes (Guest Only) (Dev 1)
Route::middleware('guest')->group(function () {
    if (class_exists(AuthController::class)) {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    } elseif (class_exists(LoginController::class)) {
        Route::get('/login', [LoginController::class, 'create'])->name('login');
        Route::post('/login', [LoginController::class, 'store'])->name('login.post');
    }

    if (class_exists(ForgotPasswordController::class)) {
        Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'] ?? [ForgotPasswordController::class, 'create'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'] ?? [ForgotPasswordController::class, 'store'])->name('password.email');
    }

    if (class_exists(ResetPasswordController::class)) {
        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'] ?? [ResetPasswordController::class, 'create'])->name('password.reset');
        Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'] ?? [ResetPasswordController::class, 'store'])->name('password.update');
    }

    if (class_exists(SetPasswordController::class)) {
        Route::get('/kol/set-password/{token?}', [SetPasswordController::class, 'showSetPasswordForm'] ?? [SetPasswordController::class, 'create'])->name('password.set');
        Route::post('/kol/set-password', [SetPasswordController::class, 'updatePassword'] ?? [SetPasswordController::class, 'store'])->name('password.set.post');
        Route::get('/set-password', [SetPasswordController::class, 'create'] ?? [SetPasswordController::class, 'showSetPasswordForm'])->name('password.set.alias');
        Route::post('/set-password', [SetPasswordController::class, 'store'] ?? [SetPasswordController::class, 'updatePassword'])->name('password.set.store');
    }
});

// Logout (Authenticated Only)
if (class_exists(AuthController::class)) {
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
} elseif (class_exists(LoginController::class)) {
    Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
}

// Notifications (Dev 5)
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});
