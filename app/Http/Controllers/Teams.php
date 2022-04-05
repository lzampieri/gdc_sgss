<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Teams extends Controller
{
    public static function saveAndCreateAndTB( Request $request ) {
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

        $newtb = $request->input( 'newteamboss' );
        if( $newtb > -1 ) {
            Teams::setTeamBoss( $newtb );
        }

        return back();
    }

    public static function myTeam() {
        if( Auth::user()->theteam )
            return Auth::user()->theteam->users;
        else return [];
    }

    public static function setTeamBoss( $userId ) {
        if( ( Auth::user()->isadmin ) || ( Settings::obtain( 'edit_team_boss' ) ) ) {
            $user = User::find( $userId );
            $team = $user->theteam;
            if( !is_null( $team ) ) {
                $team->boss = $user->id;
                $team->save();
            }
        }
        return back();
    }
}
