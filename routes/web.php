<?php

use App\Http\Controllers\Pendings;
use App\Http\Controllers\Settings;
use App\Http\Controllers\Teams;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Homepage
Route::get('/', function () { return view('home');  })->name('home');

// Pagine pubbliche
Route::get('/albo-doro', function () { return view('albo-doro'); })->middleware('auth')->name( 'albo-doro' );
Route::get('/regolamento', function () { return redirect( asset( 'files/regolamento.pdf') ); })->name( 'regolamento' );
Route::get('/terms', function () { return view('terms'); })->name( 'terms' );

// Pagine di servizio
Route::get('/ensure_settings', [ Settings::class, 'ensure' ] );
Route::get('/migrate', [ Settings::class, 'migrate' ] );

// Solo utenti registrati    
Route::middleware( ['auth'] )->group( function () {

    // Solo utenti vivi
    Route::middleware( ['auth.alive'])->group( function () {
        // Gestione claim di uccisione
        Route::get('/pending_check', function () { return view('pending-check'); } )->name('pending.check');
        Route::get('/pending_approve/{claimId}', [ Pendings::class, 'approve' ])->name('pending.approve');
        Route::get('/pending_reject/{claimId}', [ Pendings::class, 'reject' ])->name('pending.reject');
    });
    
    // Solo utenti vivi e senza claim di uccisione
    Route::middleware( ['auth.alive', 'auth.nopending'])->group( function () {
        // Dichiarazioni di uccisione
        Route::get('/pending_create/{userId}', [ Pendings::class, 'create' ])->name('pending.create');
        // Gestione squadra
        Route::get('/edit_team_boss/{userId}', [ Teams::class, 'setTeamBoss'] )->name( 'edit_team_boss' );
    });
});



// Altro
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';