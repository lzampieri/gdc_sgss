<?php

use App\Http\Controllers\Users;
use Laravel\Socialite\Facades\Socialite;
 
Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.redirect');
 
Route::get('/auth/callback', function () {
    return Users::login( Socialite::driver('google')->user()->email );
})->name('auth.callback');

Route::get('/signup', function () { return view( 'signup' ); } )->name( 'signup' );
Route::post('/signup', [ Users::class, 'create' ] );

Route::get('/signoff', function () { Auth::logout(); return redirect( route('home') ); } )->name( 'signoff' );
