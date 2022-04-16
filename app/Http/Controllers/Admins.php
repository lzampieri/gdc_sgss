<?php

namespace App\Http\Controllers;

use App\Logging\Logger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mailer;
use App\Models\Event;
use App\Models\PendingKill;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Boolean;

class Admins extends Controller
{
    public static function add_event( Request $request ) {
        
        $validated = $request->validate([
            'actor' => 'required|exists:users,id',
            'target' => 'required|exists:users,id',
            'finalState' => 'required|boolean'
        ]);
        $event = Event::create( [
            'actor' => $validated['actor'],
            'target' => $validated['target'],
            'finalstate' => $validated['finalState']
        ]);

        Mailer::event_created( $event );

        if( $validated['finalState'] == false ) {
            $resuscitation = Pendings::resuscitate( $event );
            
            if( $resuscitation )
                return $resuscitation;
        }

        Log::info("Created event", Logger::logParams(['event' => $event] ) );

        return back()->with( 'positive-message', 'Evento creato.');
    }

    public static function add_pending( Request $request ) {
        $validated = $request->validate([
            'actor' => 'required|exists:users,id',
            'target' => 'required|exists:users,id'
        ]);
        $pending = PendingKill::create( [
            'actor' => $validated['actor'],
            'target' => $validated['target'],
        ]);

        Mailer::pending_create( $pending );

        Log::info("Created pending event", Logger::logParams(['pending_event' => $pending] ) );

        return back()->with( 'positive-message', 'Evento supposto creato, mail inviata.');
    }
}
