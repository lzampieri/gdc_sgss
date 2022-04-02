<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mailer;

class Users extends Controller
{

    public static function login( $email ) {
        // Check if user exists
        $user = User::where( 'email', $email )->first();

        // If the user exists, login and redirect to homepage
        if( $user ) {
            Auth::login( $user );
            return redirect( route('home') );
        }

        // Else, return the signup module
        return redirect( route( 'signup' ) )->with( 'email', $email );
    }

    public static function create( Request $request ) {
        $validated = $request->validate([
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'conditions' => 'accepted'
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'name' => $validated['name']
        ]);

        Auth::login( $user );

        Mailer::welcome_mail();

        return redirect( route('home') )->with( 'positive-message', 'Iscrizione correttamente effettuata!<br/>Ti è stata inviata una mail di conferma.');
    }
}
