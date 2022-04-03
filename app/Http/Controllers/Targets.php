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
            if( $index == False ) {
                return [ ];
            }
            do {
                $target = User::find( $cycle[ ( ++$index ) % count( $cycle ) ] );
            } while( !$target->isalive );
            return [ $target ];
        }
        // Single cycle with double target
        if( $method == 'single_double' ) {
            // Load the cycle
            $cycle = json_decode( Settings::obtain( 'single_cycle' ) );
            $index = array_search( Auth::user()->id, $cycle );
            if( $index === False ) {
                return [ ];
            }
            do {
                $target1 = User::find( $cycle[ ( ++$index ) % count( $cycle ) ] );
            } while( !$target1->isalive );
            do {
                $target2 = User::find( $cycle[ ( ++$index ) % count( $cycle ) ] );
            } while( !$target2->isalive );
            return [ $target1, $target2 ];
        }
    }

}
