<?php

use App\Http\Controllers\Events;
use App\Http\Controllers\Settings;
use App\Http\Controllers\Users;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::middleware( ['admin'] )->group( function () {

    // Manage the database
    Route::get('/migrate', function () { return Artisan::call('migrate'); });
    Route::get('/addoption/{key}/{value}', [ Settings::class, 'addoption' ] );

    // Admin frontend
    Route::get('/admin', function () { return view('admin.main'); })->name('admin.main');
    Route::get('/admin/deleted', function () { return view('admin.deleted'); })->name('admin.deleted');

    // User admin
    Route::get('/user/admin/{id}', [ Users::class, 'makeadmin' ] )->name('user.admin');
    Route::get('/user/deadmin/{id}', [ Users::class, 'deadmin' ] )->name('user.deadmin');
    Route::get('/user/trash/{id}', [ Users::class, 'maketrash' ] )->name('user.trash');
    Route::get('/user/detrash/{id}', [ Users::class, 'detrash' ] )->name('user.detrash');
    Route::get('/user/kill/{id}', [ Users::class, 'makekill' ] )->name('user.kill');
    Route::get('/user/dekill/{id}', [ Users::class, 'dekill' ] )->name('user.dekill');

    // Events admin
    Route::get('/event/trash/{id}', [ Events::class, 'maketrash' ] )->name('event.trash');
    Route::get('/event/detrash/{id}', [ Events::class, 'detrash' ] )->name('event.detrash');
    
});