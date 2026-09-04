<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Superadmin Routes
|--------------------------------------------------------------------------
| Prefix: /admin
| Name: admin.
| Middleware: web, auth, role:superadmin
*/

Route::get('/dashboard', function () {
    return response('<!DOCTYPE html>
    <html lang="id">
    <head><title>Admin Dashboard</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><style>body{font-family:system-ui,-apple-system,sans-serif;background:#0d1117;color:#f0f6fc;display:flex;align-items:center;justify-content:center;height:100vh;margin:0}.card{background:#161b22;border:1px solid #30363d;border-radius:12px;padding:32px;text-align:center;box-shadow:0 8px 24px rgba(0,0,0,0.4);max-width:480px}h1{color:#e6a15c;margin-top:0}p{color:#8b949e}button{background:#b84d31;color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:600;margin-top:16px}</style></head>
    <body>
        <div class="card">
            <h1>🛡️ Admin Dashboard</h1>
            <p>Selamat datang, <strong>'.e(auth()->user()->name).'</strong>!</p>
            <p>Role: <code>'.e(auth()->user()->roles->pluck('name')->implode(', ')).'</code></p>
            <p><em>(Placeholder untuk Dev 2 & 3)</em></p>
            <form method="POST" action="'.route('logout').'">
                '.csrf_field().'
                <button type="submit">Logout</button>
            </form>
        </div>
    </body></html>');
})->name('dashboard');
use App\Http\Controllers\Admin\KolManagementController;
use App\Http\Controllers\Admin\RegistrationReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    // Note: 'role:superadmin' middleware will be created by Dev 1. For now, we just use auth.
    
    // Pendaftaran
    Route::get('/pendaftaran', [RegistrationReviewController::class, 'index'])->name('registrations.index');
    Route::get('/pendaftaran/{registration}', [RegistrationReviewController::class, 'show'])->name('registrations.show');
    Route::post('/pendaftaran/{registration}/approve', [RegistrationReviewController::class, 'approve'])->name('registrations.approve');
    Route::post('/pendaftaran/{registration}/reject', [RegistrationReviewController::class, 'reject'])->name('registrations.reject');

    // Manajemen KOL
    Route::get('/kol/export', [KolManagementController::class, 'export'])->name('kol.export');
    Route::resource('/kol', KolManagementController::class)->except(['destroy']);
    Route::patch('/kol/{kol}/status', [KolManagementController::class, 'updateStatus'])->name('kol.update-status');
});
