<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Settings;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Targets extends Controller
{

    public function targets( ) {
        $method = Settings::obtain( 'method' );
        
        // Single cycle with single target
        if( $method == 'single_single' ) {
            // Load the cycle
            $cycle = json_decode( Settings::obtain( 'single_cycle' ) );
            $index = array_search( Auth::user()->id, $cycle );
            if( $index === False ) {
                return [ ];
            }
            do {
                $target = User::find( $cycle[ ( ++$index ) % count( $cycle ) ] );
            } while( !$target->is_alive );
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
            } while( !$target1->is_alive );
            do {
                $target2 = User::find( $cycle[ ( ++$index ) % count( $cycle ) ] );
            } while( !$target2->is_alive );
            return [ $target1, $target2 ];
        }
        // Single cycle with single target
        if( $method == 'teams_single_single' ) {
            // Load the cycle
            $cycle = json_decode( Settings::obtain( 'teams_cycle' ) );
            $theteam = Auth::user()->theteam;
            if( $theteam == null ) {
                return [];
            }
            $index = array_search( $theteam->id, $cycle );
            if( $index === False ) {
                return [];
            }
            do {
                $target = Team::find( $cycle[ ( ++$index ) % count( $cycle ) ] );
            } while( !$target->anyAlive() );
            
            return [ $target ];
        }

        // No targets
        return [];
    }

}
