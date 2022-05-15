<?php

namespace App\Logging;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class Logger {
    public static function parseDates( $item ) {
        return " [Created " . $item->created_at->format('h:i d/m') . ", updated " . $item->updated_at->format('h:i d/m') . ( isset( $item->deleted_at ) ? ", deleted " . $item->deleted_at->format('h:i d/m') : "" ) . "]";
    }

    public static function logParams( $params ) {
        # Parse known params
        if( isset( $params['event'] ) ) {
            $event = $params['event'];
            $params['event'] = ( $event->finalstate ? 'Resurrezione' : 'Morte' ) . ' di ' . $event->thetarget->name . ' a mano di ' . $event->theactor->name . Logger::parseDates($event);
        }
        if( isset( $params['pending_event'] ) ) {
            $pending = $params['pending_event'];
            $params['pending_event'] = 'Presunta morte di ' . $pending->thetarget->name . ' a mano di ' . $pending->theactor->name . Logger::parseDates($pending);
        }
        if( isset( $params['setting'] ) ) {
            $setting = $params['setting'];
            $params['setting'] = $setting->key . ": " . $setting->value;
        }
        if( isset( $params['user'] ) ) {
            $user = $params['user'];
            $params['user'] = $user->name . " [" . $user->email . "]";
            if( isset( $user->team ) )
                $params['user'] .= " Team " . $user->team;
            if( isset( $params['prev_team'] ) ) {
                $params['user'] .= " (previously " . $params['prev_team'] . ")";
                unset( $params['prev_team'] );
            }
            if( isset( $user->isadmin ) && $user->isadmin )
                $params['user'] .= " ADMIN";
            if( isset( $user->deleted_at ) )
                $params['user'] .= " (deleted " . $user->deleted_at->format('h:i d/m') . ")";
        }
        if( isset( $params['team'] ) ) {
            $params['team'] = "Team " . $params['team']->id;
        }
        if( isset( $params['passcode'] ) ) {
            $params['passcode'] = "Passcode " . $params['passcode']->title . " (" . $params['passcode']->id . ")";
        }
        if( isset( $params['task'] ) ) {
            $params['task'] = "Task " . $params['task']->explain() . " with passcode " . $params['task']->thepasscode->name . " (" . $params['task']->thepasscode->id . ")";
        }
        return array_merge( $params, [
            'identity' => ( Auth::check() ? Auth::user()->email : "Guest: " . Request::ip() )
        ]);
    }

    public static function telegramInfo( $text ) {
        Log::channel('telegram')->info( $text );
    }
}