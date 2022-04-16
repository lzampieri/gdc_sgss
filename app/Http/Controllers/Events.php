<?php

namespace App\Http\Controllers;

use App\Logging\Logger;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class Events extends Controller
{
    public static function maketrash( $id ) {
        $event = Event::find( $id );
        $event->delete();
        Log::info("Deleted event", Logger::logParams(['event' => $event] ) );
        return back();
    }
    public static function detrash( $id ) {
        $event = Event::withTrashed()->find( $id );
        $event->restore();
        Log::info("Restored event", Logger::logParams(['event' => $event] ) );
        return back();
    }
}
