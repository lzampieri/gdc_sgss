<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Settings;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Targets extends Controller
{

    public function targets( ) {
        $user = Auth::user();
        $method = Settings::obtain( 'method' );
        
        // Single cycle with single target
        if( $method == 'single_single' ) {
            // Load the cycle
            $cycle = json_decode( Settings::obtain( 'single_cycle' ) );
            $index = array_search( Auth::user()->id, $cycle );
            return [
                User::find( $cycle[ ($index + 1 ) % count( $cycle ) ] )
            ];
        }
        // Single cycle with double target
        if( $method == 'single_double' ) {
            // Load the cycle
            $cycle = json_decode( Settings::obtain( 'single_cycle' ) );
            $index = array_search( Auth::user()->id, $cycle );
            return [
                User::find( $cycle[ ($index + 1 ) % count( $cycle ) ] ),
                User::find( $cycle[ ($index + 2 ) % count( $cycle ) ] )
            ];
        }
    }

}
