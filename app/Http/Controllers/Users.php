<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Users extends Controller
{

    public function login( $email ) {
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

    public function create( Request $request ) {
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

        return redirect( route('home') )->with( 'positive-message', 'Iscrizione correttamente effettuata!');
    }
}
