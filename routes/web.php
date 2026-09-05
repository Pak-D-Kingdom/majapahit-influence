<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});

// Superadmin Routes (prefix: /admin, as: superadmin.)
Route::prefix('admin')->as('superadmin.')->group(function () {
    require __DIR__.'/superadmin.php';
});

// KOL Routes (prefix: /kol, as: kol.)
Route::prefix('kol')->as('kol.')->group(function () {
    require __DIR__.'/kol.php';
});