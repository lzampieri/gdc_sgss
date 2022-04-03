<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class Teams extends Controller
{
    public static function saveAndCreate( Request $request ) {
        $assoc = json_decode( $request->input( 'squads' ) );
        foreach ($assoc as $player => $team) {
            $u = User::find( $player );
            $u->team = ( $team == -1 ? null : $team );
            $u->save();
        }

        $newsquad = $request->input( 'newteam' );
        if( $newsquad == 1 ) {
            Team::create();
        }

        return back();
    }
}
