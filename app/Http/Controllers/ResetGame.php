<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Admin;
use App\Logging\Logger;
use App\Logging\TelegramLogger;
use App\Models\Event;
use App\Models\PendingKill;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResetGame extends Controller
{
    public static function get_reset_key() {
        $string = '';
        $length = 11;
        $vowels = array("a","e","i","o","u");  
        $consonants = array(
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
        );  
        $max = $length / 2;
        for ($i = 1; $i <= $max; $i++)
        {
            $string .= $consonants[rand(0,19)];
            $string .= $vowels[rand(0,4)];
        }
    
        Settings::updoption( 'reset_code', $string );

        return strrev( $string );    
    }

    public static function reset( Request $request ) {
        $validated = $request->validate([
            'the_reset_pass' => 'required|string'
        ]);

        $the_actual_reset_pass = Settings::obtain( 'reset_code' );

        Log::info("Started doing reset...", Logger::logParams( [] ) );
        
        if( $validated['the_reset_pass'] == $the_actual_reset_pass ) {
            Admins::do_backup();
            
            PendingKill::all()->map->delete();
            
            Event::all()->map->delete();
            
            Team::all()->map->delete();
            
            User::where( 'isadmin', False )->get()->map->delete();

            Log::info("Total reset done!", Logger::logParams( [] ) );
            TelegramLogger::reset_done();

            Settings::updoption( 'last_reset', now() );

            return redirect( route( 'admin.main' ) );
        }
        

        return back()->with( 'negative-message', 'Hai sbagliato il codice di controllo. Sei per caso un robot che cerca di rompere il sito?');
    }
}
