<?php

use App\Http\Controllers\Admins;
use App\Http\Controllers\Events;
use App\Http\Controllers\Settings;
use App\Http\Controllers\Teams;
use App\Http\Controllers\Users;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::middleware( ['auth.admin'] )->group( function () {

    // Manage the database
    Route::get('/migrate', function () { return Artisan::call('migrate'); });
    Route::get('/option/{key}/{value}', [ Settings::class, 'updoption' ] )->name('option.update');


    // Admin frontend
    Route::get('/admin', function () { return view('admin.main'); })->name('admin.main');
    Route::post('/admin/add_event', [ Admins::class, 'add_event' ] )->name('admin.add.event');
    Route::post('/admin/add_pending', [ Admins::class, 'add_pending' ] )->name('admin.add.pending');
    Route::get('/admin/deleted', function () { return view('admin.deleted'); })->name('admin.deleted');
    Route::get('/admin/option', function () { return view('admin.options'); })->name('admin.option');
    Route::get('/admin/teams', function () { return view('admin.teams'); })->name('admin.teams');
    Route::post('/admin/teams', [ Teams::class, 'saveAndCreateAndTB'] );
    Route::get('/admin/cycles/single', function () { return view('admin.cycles.single'); })->name('admin.cycles.single');
    Route::post('/admin/cycles/single', function (Request $request) { return redirect( route('option.update', [ 'key' => 'single_cycle', 'value' => $request->input('cycle') ] ) ); });
    Route::get('/admin/cycles/teams', function () { return view('admin.cycles.teams'); })->name('admin.cycles.teams');
    Route::post('/admin/cycles/teams', function (Request $request) { return redirect( route('option.update', [ 'key' => 'teams_cycle', 'value' => $request->input('cycle') ] ) ); });


    // Users admin
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