<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});
Route::get('/explore/creators', function () {
    return view('explore.creators');
});
Route::get('/explore/brands', function () {
    return view('explore.brands');
});