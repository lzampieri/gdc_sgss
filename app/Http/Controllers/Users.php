<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mailer;
use App\Logging\Logger;
use App\Models\Event;

class Users extends Controller
{
    public static function login( $email ) {

        // Check if user exists
        $user = User::where( 'email', $email )->first();

        // If the user exists, login and redirect to homepage
        if( $user ) {
            Auth::login( $user );
            return redirect()->intended( route('home') );
        }

        // Check if the user exists but it is trashed
        $user = User::onlyTrashed()->where( 'email', $email )->first();
        
        // If the user exists trashed, redirect to signup with user data
        return redirect( route( 'signup') )->with( 'email', $email )->with( 'trashed', $user );

        // Else, return the signup module
        return redirect( route( 'signup' ) )->with( 'email', $email );
    }

    public static function createOrUpdate( Request $request ) {
        $validated = $request->validate([
            'email' => 'required',
            'name' => 'required',
            'birthday' => 'required|date|before:today',
            'conditions' => 'accepted'
        ]);

        // Check if the user already exists
        $user = User::withTrashed()->where( 'email', $validated['email'] )->first();

        // If the user already exists, perform update
        if( $user ) {

            if( $user->trashed() ) {
                $user->restore();
                Log::info("User restored", Logger::logParams( [ 'user' => $user ] ) );
            }

            $user->name = $validated['name'];
            $user->birthday = $validated['birthday'];
            $user->save();

            Auth::login( $user );

            Mailer::welcome_mail();

            Log::info("User updated", Logger::logParams( [ 'user' => $user ] ) );

            return redirect( route('home') )->with( 'positive-message', 'Iscrizione correttamente effettuata!<br/>Ti è stata inviata una mail di conferma.<br/>Verifica di averla ricevuta, controllando anche la cartella SPAM.');
        }

        // If the user still not exists, perform creation
        $user = User::create([
            'email' => $validated['email'],
            'name' => $validated['name'],
            'birthday' => $validated['birthday']
        ]);

        Auth::login( $user );

        Mailer::welcome_mail();

        Log::info("User created", Logger::logParams( [ 'user' => $user ] ) );

        return redirect( route('home') )->with( 'positive-message', 'Iscrizione correttamente effettuata!<br/>Ti è stata inviata una mail di conferma.<br/>Verifica di averla ricevuta, controllando anche la cartella SPAM.');
    }

    public static function makeadmin( $id ) {
        $user = User::find( $id );
        $user->isadmin = True;
        $user->save();
        Log::info("User promoted to admin", Logger::logParams( [ 'user' => $user ] ) );
        return back();
    }
    public static function deadmin( $id ) {
        $user = User::find( $id );
        if( $user == Auth::user() ) {
            return back()->with( 'negative-message', 'Non puoi arretrare te stesso.');
        }
        $user->isadmin = False;
        $user->save();
        Log::info("User revoked from admin", Logger::logParams( [ 'user' => $user ] ) );
        return back();
    }

    public static function maketrash( $id ) {
        $user = User::find( $id );
        if( $user == Auth::user() ) {
            return back()->with( 'negative-message', 'Non puoi eliminare te stesso.');
        }
        $user->isadmin = False;
        $user->save();
        $user->delete();
        Log::info("User deleted", Logger::logParams( [ 'user' => $user ] ) );
        return back();
    }
    public static function detrash( $id ) {
        $user = User::withTrashed()->find( $id );
        $user->restore();
        Log::info("User restored", Logger::logParams( [ 'user' => $user ] ) );
        return back();
    }
}
