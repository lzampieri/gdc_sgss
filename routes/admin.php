<?php

use App\Exports\ExportsController;
use App\Http\Controllers\Admins;
use App\Http\Controllers\CronJobs;
use App\Http\Controllers\Events;
use App\Http\Controllers\RollOfHonorEntries;
use App\Http\Controllers\Settings;
use App\Http\Controllers\Teams;
use App\Http\Controllers\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware( ['auth.admin'] )->group( function () {

    // Manage the database
    Route::get('/option/{key}/{value}', [ Settings::class, 'updoption' ] )->name('option.update');


    // Admin frontend
    Route::get('/admin', function () { return view('admin.main'); })->name('admin.main');

    // Event management
    Route::post('/admin/add_event', [ Admins::class, 'add_event' ] )->name('admin.add.event');
    Route::get('/admin/deleted', function () { return view('admin.deleted'); })->name('admin.deleted');
    Route::get('/event/trash/{id}', [ Events::class, 'maketrash' ] )->name('event.trash');
    Route::get('/event/detrash/{id}', [ Events::class, 'detrash' ] )->name('event.detrash');

    // Option management
    Route::get('/admin/option', function () { return view('admin.options'); })->name('admin.option');
    Route::post('/admin/option', [ Admins::class, 'set_communication'] );

    // Teams management
    Route::get('/admin/teams', function () { return view('admin.teams'); })->name('admin.teams');
    Route::post('/admin/teams', [ Teams::class, 'saveAndCreateAndTB'] );

    // Cycle management
    Route::get('/admin/cycles/single', function () { return view('admin.cycles.single'); })->name('admin.cycles.single');
    Route::post('/admin/cycles/single', function (Request $request) { return redirect( route('option.update', [ 'key' => 'single_cycle', 'value' => $request->input('cycle') ] ) ); });

    // Teams cycle management
    Route::get('/admin/cycles/teams', function () { return view('admin.cycles.teams'); })->name('admin.cycles.teams');
    Route::post('/admin/cycles/teams', function (Request $request) { return redirect( route('option.update', [ 'key' => 'teams_cycle', 'value' => $request->input('cycle') ] ) ); });

    // Exports
    Route::get('/admin/exports', function () { return view('admin.exports'); })->name('admin.exports');
    Route::get('/admin/export/{table}/{type}', [ ExportsController::class, 'export' ] )->name('admin.export');

    // Tasks management
    Route::get('/admin/tasks', function () { return view('admin.tasks'); })->name('admin.tasks');
    Route::post('/admin/cronjob/add', [ CronJobs::class, 'addPasscode' ])->name('admin.cronjob.add');
    Route::get('/admin/cronjob/delete/{id}', [ CronJobs::class, 'deletePasscode' ])->name('admin.cronjob.delete');
    Route::post('/admin/task/add/{type}', [ CronJobs::class, 'addTask' ])->name('admin.task.add');
    Route::get('/admin/task/delete/{id}', [ CronJobs::class, 'deleteTask' ])->name('admin.task.delete');

    // Stats
    Route::get('/admin/stats', function () { return view('admin.stats'); })->name('admin.stats');

    // Users management
    Route::get('/user/admin/{id}', [ Users::class, 'makeadmin' ] )->name('user.admin');
    Route::get('/user/deadmin/{id}', [ Users::class, 'deadmin' ] )->name('user.deadmin');
    Route::get('/user/trash/{id}', [ Users::class, 'maketrash' ] )->name('user.trash');
    Route::get('/user/detrash/{id}', [ Users::class, 'detrash' ] )->name('user.detrash');
    
    // Roll of Honor management
    Route::get('/admin/roll-of-honors', function () { return view('admin.roll-of-honors'); })->name('admin.roll-of-honors');
    Route::post('/admin/roll-of-honors', [ RollOfHonorEntries::class, 'update_entry' ] );
    Route::get('/admin/roll-of-honors/new', [ RollOfHonorEntries::class, 'create_entry' ] )->name('admin.roll-of-honors.new');

    // View the log
    Route::get('/admin/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('admin.logs');
});