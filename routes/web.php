<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SetPasswordController;
use App\Http\Controllers\BrandRegistrationController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RegistrationController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

// Public Landing Page
Route::get('/', function () {
    $featuredProducts = collect();
    try {
        if (Schema::hasTable('products')) {
            $featuredProducts = Product::where('is_active', true)
                ->with(['brand', 'category'])
                ->take(4)
                ->get();
        }
    } catch (Throwable) {
        $featuredProducts = collect();
    }

    if (view()->exists('landing.index')) {
        return view('landing.index', compact('featuredProducts'));
    }

    return response()->json(['message' => 'Majapahit Influence API is running']);
})->name('home');

// Public KOL Registration (Dev 2 & Frontend)
Route::get('/daftar', [RegistrationController::class, 'create'])->name('registration.create');
Route::get('/daftar-kol', [RegistrationController::class, 'create'])->name('public.register');
Route::post('/daftar', [RegistrationController::class, 'store'])->name('registration.store');
Route::post('/daftar-kol', [RegistrationController::class, 'store'])->name('public.register.store');
Route::get('/daftar/konfirmasi/{registration?}', [RegistrationController::class, 'confirmation'])->name('registration.confirmation');
Route::get('/daftar-kol/konfirmasi/{registration?}', [RegistrationController::class, 'confirmation'])->name('public.register.confirmation');

// Public Brand Registration (Self-serve Brand Onboarding)
Route::get('/daftar-brand', [BrandRegistrationController::class, 'create'])->name('brand.register');
Route::post('/daftar-brand', [BrandRegistrationController::class, 'store'])->name('brand.register.store');
Route::get('/daftar-brand/konfirmasi/{registration}', [BrandRegistrationController::class, 'confirmation'])->name('brand.register.confirmation');

// Evermos-Style Product Catalog & Brand Content Bank
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/ecommerce', fn () => redirect()->route('catalog.index'))->name('ecommerce');
Route::get('/katalog/{product:slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/katalog/{product:slug}/bank-konten', [CatalogController::class, 'contentBank'])->name('catalog.content-bank');

// Authentication Routes (Guest Only) (Dev 1)
Route::middleware('guest')->group(function () {
    if (class_exists(AuthController::class)) {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
        Route::post('/login/store', [AuthController::class, 'login'])->name('login.store');
        Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    } elseif (class_exists(LoginController::class)) {
        Route::get('/login', [LoginController::class, 'create'])->name('login');
        Route::post('/login', [LoginController::class, 'store'])->name('login.store');
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
