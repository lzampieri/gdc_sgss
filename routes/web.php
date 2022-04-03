<?php

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

Route::get('/albo-doro', function () { return view('albo-doro'); })->name( 'albo-doro' );
Route::get('/regolamento', function () { return redirect( asset( 'files/regolamento.pdf') ); })->name( 'regolamento' );
Route::get('/terms', function () { return view('terms'); })->name( 'terms' );


require __DIR__.'/auth.php';
require __DIR__.'/admin.php';