<?php

use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\EndorsementController;
use App\Http\Controllers\Admin\KolManagementController;
use App\Http\Controllers\Admin\RegistrationReviewController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Superadmin\AuditTrailController;
use App\Http\Controllers\Superadmin\BrandController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Superadmin & Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    if (view()->exists('superadmin.dashboard')) {
        return view('superadmin.dashboard');
    }
    return response()->json([
        'message' => 'Admin Dashboard',
        'user' => auth()->user(),
    ]);
})->name('dashboard');

// Dev 2: Pendaftaran Review
Route::get('/pendaftaran', [RegistrationReviewController::class, 'index'])->name('registrations.index');
Route::get('/pendaftaran/{registration}', [RegistrationReviewController::class, 'show'])->name('registrations.show');
Route::post('/pendaftaran/{registration}/approve', [RegistrationReviewController::class, 'approve'])->name('registrations.approve');
Route::post('/pendaftaran/{registration}/reject', [RegistrationReviewController::class, 'reject'])->name('registrations.reject');

// Dev 2: Manajemen KOL
Route::get('/kol/export', [KolManagementController::class, 'export'])->name('kol.export');
Route::resource('/kol', KolManagementController::class)->except(['destroy']);
Route::patch('/kol/{kol}/status', [KolManagementController::class, 'updateStatus'])->name('kol.update-status');

// Dev 3: Superadmin Brand Management
Route::resource('brands', BrandController::class);

// Dev 3: Superadmin Campaign Management
Route::resource('campaigns', CampaignController::class);

// Dev 3: Superadmin KOL Assignment & Endorsement Lifecycle
Route::post('campaigns/{campaign}/assign', [EndorsementController::class, 'assign'])->name('campaigns.assign');
Route::post('campaigns/{campaign}/endorsements', [EndorsementController::class, 'assign'])->name('campaigns.endorsements.store');
Route::post('endorsements/{endorsement}/review', [EndorsementController::class, 'reviewProof'])->name('endorsements.review');
Route::post('endorsements/{endorsement}/complete', [EndorsementController::class, 'complete'])->name('endorsements.complete');
Route::delete('endorsements/{endorsement}', [EndorsementController::class, 'destroy'])->name('endorsements.destroy');

// Dev 4: Commission Management
Route::get('commissions/export', [CommissionController::class, 'export'])->name('commissions.export');
Route::get('commissions', [CommissionController::class, 'index'])->name('commissions.index');
Route::get('commissions/{commission}', [CommissionController::class, 'show'])->name('commissions.show');
Route::post('commissions/approve', [CommissionController::class, 'approve'])->name('commissions.approve');
Route::post('commissions/{commission}/process', [CommissionController::class, 'process'])->name('commissions.process');

// Dev 4: Reports
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/commissions/export', [ReportController::class, 'exportCommissions'])->name('reports.commissions.export');
Route::get('reports/kol/export', [ReportController::class, 'exportKol'])->name('reports.kol.export');

// Audit Trail & Notifications
if (class_exists(AuditTrailController::class)) {
    Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('audit.index');
}
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
