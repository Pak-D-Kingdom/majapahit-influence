<?php

use App\Http\Controllers\Admin\BrandRegistrationReviewController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\ContentBankController;
use App\Http\Controllers\Admin\EndorsementController;
use App\Http\Controllers\Admin\KolManagementController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\ProductVerificationController;
use App\Http\Controllers\Admin\RegistrationReviewController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Superadmin\AuditTrailController;
use App\Http\Controllers\Superadmin\BrandController;
use App\Http\Controllers\Superadmin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Superadmin & Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', DashboardController::class)->name('dashboard');

// Dev 2: Pendaftaran Review (KOL)
Route::get('/pendaftaran', [RegistrationReviewController::class, 'index'])->name('registrations.index');
Route::get('/pendaftaran/{registration}', [RegistrationReviewController::class, 'show'])->name('registrations.show');
Route::post('/pendaftaran/{registration}/approve', [RegistrationReviewController::class, 'approve'])->name('registrations.approve');
Route::post('/pendaftaran/{registration}/reject', [RegistrationReviewController::class, 'reject'])->name('registrations.reject');
Route::post('/pendaftaran/{registration}/review', [RegistrationReviewController::class, 'review'])->name('registrations.review');

// Self-Serve Brand Registration Reviews
Route::get('brand-registrations', [BrandRegistrationReviewController::class, 'index'])->name('brand-registrations.index');
Route::get('brand-registrations/{brandRegistration}', [BrandRegistrationReviewController::class, 'show'])->name('brand-registrations.show');
Route::post('brand-registrations/{brandRegistration}/approve', [BrandRegistrationReviewController::class, 'approve'])->name('brand-registrations.approve');
Route::post('brand-registrations/{brandRegistration}/reject', [BrandRegistrationReviewController::class, 'reject'])->name('brand-registrations.reject');

// Dev 2: Manajemen KOL
Route::get('/kol/export', [KolManagementController::class, 'export'])->name('kol.export');
Route::resource('/kol', KolManagementController::class)->except(['destroy']);
Route::patch('/kol/{kol}/status', [KolManagementController::class, 'updateStatus'])->name('kol.update-status');

// Superadmin Product Catalog & Content Bank Management
Route::resource('products', ProductManagementController::class);
Route::post('products/{product}/toggle-publish', [ProductManagementController::class, 'togglePublish'])->name('products.toggle-publish');
Route::post('products/{product}/content-banks', [ContentBankController::class, 'store'])->name('products.content-banks.store');
Route::put('content-banks/{contentBank}', [ContentBankController::class, 'update'])->name('content-banks.update');
Route::delete('content-banks/{contentBank}', [ContentBankController::class, 'destroy'])->name('content-banks.destroy');

// Dev 6: Product Verification
Route::get('product-verifications', [ProductVerificationController::class, 'index'])->name('product-verifications.index');
Route::post('product-verifications/{product}/verify', [ProductVerificationController::class, 'verify'])->name('product-verifications.verify');

// Dev 3: Superadmin Brand Management
Route::resource('brands', BrandController::class);

// Dev 3: Superadmin Campaign Management
Route::resource('campaigns', CampaignController::class);

// Dev 3: Superadmin KOL Assignment & Endorsement Lifecycle
Route::get('endorsements', [App\Http\Controllers\Superadmin\EndorsementController::class, 'index'])->name('endorsements.index');
Route::get('endorsements/{endorsement}', [App\Http\Controllers\Superadmin\EndorsementController::class, 'show'])->name('endorsements.show');
Route::get('endorsements/{endorsement}/edit', [App\Http\Controllers\Superadmin\EndorsementController::class, 'edit'])->name('endorsements.edit');
Route::put('endorsements/{endorsement}', [App\Http\Controllers\Superadmin\EndorsementController::class, 'update'])->name('endorsements.update');
Route::get('campaigns/{campaign}/assign', fn ($campaign) => redirect()->route('superadmin.campaigns.show', $campaign))->name('campaigns.assign.view');
Route::post('campaigns/{campaign}/assign', [EndorsementController::class, 'assign'])->name('campaigns.assign');
Route::post('campaigns/{campaign}/endorsements', [EndorsementController::class, 'assign'])->name('campaigns.endorsements.store');
Route::post('endorsements/{endorsement}/review', [EndorsementController::class, 'reviewProof'])->name('endorsements.review');
Route::post('endorsements/{endorsement}/proofs/{proof}/review', [App\Http\Controllers\Superadmin\EndorsementController::class, 'reviewProof'])->name('endorsements.proof.review');
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
Route::match(['GET', 'POST', 'PATCH'], '/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
