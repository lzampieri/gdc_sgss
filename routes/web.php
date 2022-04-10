<?php

use App\Http\Controllers\Pendings;
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

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/albo-doro', function () { return view('albo-doro'); })->middleware('auth')->name( 'albo-doro' );
Route::get('/regolamento', function () { return redirect( asset( 'files/regolamento.pdf') ); })->name( 'regolamento' );
Route::get('/terms', function () { return view('terms'); })->name( 'terms' );

Route::middleware( ['auth'] )->group( function () {
    // Kill and confirm
    Route::get('/pending_create/{userId}', [ Pendings::class, 'create' ])->name('pending.create');
    Route::get('/pending_approve/{claimId}', [ Pendings::class, 'approve' ])->name('pending.approve');
    Route::get('/pending_reject/{claimId}', [ Pendings::class, 'reject' ])->name('pending.reject');

    // Various
    Route::get('/edit_team_boss/{userId}', [ Teams::class, 'setTeamBoss'] )->name( 'edit_team_boss' );
});


require __DIR__.'/auth.php';
require __DIR__.'/admin.php';