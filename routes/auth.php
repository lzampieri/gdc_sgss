<?php

use App\Http\Controllers\Users;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->scopes([ 'email' ])->redirect();
})->name('auth.redirect');
 
Route::get('/auth/callback', function () {
    try {
        return Users::login( Socialite::driver('google')->user()->email );
    } catch ( InvalidStateException $e ) {
        return redirect( route('home') )->with( 'negative-message', 'C\'è stato un problema di comunicazione con Google. Riprova, per favore.');
    }
})->name('auth.callback');

Route::get('/signup', function () { return view( 'signup' ); } )->name( 'signup' );
Route::post('/signup', [ Users::class, 'createOrUpdate' ] );

Route::get('/signoff', function () { Auth::logout(); return redirect( route('home') ); } )->name( 'signoff' );
