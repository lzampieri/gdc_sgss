<?php

namespace App\Http\Controllers;

use App\Models\Event;

class Events extends Controller
{
    public static function maketrash( $id ) {
        $event = Event::find( $id );
        $event->delete();
        return back();
    }
    public static function detrash( $id ) {
        $event = Event::withTrashed()->find( $id );
        $event->restore();
        return back();
    }
}
