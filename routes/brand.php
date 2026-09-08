<?php

use App\Http\Controllers\Brand\CampaignController;
use App\Http\Controllers\Brand\DashboardController;
use App\Http\Controllers\Brand\EndorsementController;
use App\Http\Controllers\Brand\ProductController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('products', ProductController::class);
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/endorsements', [EndorsementController::class, 'index'])->name('endorsements.index');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
