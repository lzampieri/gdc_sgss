<?php

use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::middleware( ['admin'] )->group( function () {

    // Manage the database
    Route::get('/migrate', function () { return Artisan::call('migrate'); });
    Route::get('/addoption/{key}/{value}', [ Settings::class, 'addoption' ] );

    // Admin frontend
    Route::get('/admin', function () { return view('admin.main'); })->name('admin.main');

});