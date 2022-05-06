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
            'finalState' => 'required|int'
        ]);

        $sendmail = $request->has( 'sendmail' );
        $resurrections = $request->has( 'resurrections' );

        if( $validated['finalState'] >= 0 ) {
            $event = Event::create( [
                'actor' => $validated['actor'],
                'target' => $validated['target'],
                'finalstate' => $validated['finalState']
            ]);
            Log::info("Created event", Logger::logParams(['event' => $event] ) );

            if( $sendmail ) {
                Mailer::event_created( $event );
            }

            if( ( $validated['finalState'] == 0 ) &&  $resurrections ) {
                $resuscitation = Pendings::resuscitate( $event, $sendmail );

                if( $resuscitation )
                    return $resuscitation;
            }
        
            return back()->with( 'positive-message', 'Evento creato.');
        }
        if( $validated['finalState'] == -1 ) {
            $pending = PendingKill::create( [
                'actor' => $validated['actor'],
                'target' => $validated['target']
            ]);
            Log::info("Created pending event", Logger::logParams(['pending_event' => $pending] ) );

            if( $sendmail ) {
                Mailer::pending_create( $pending );
            }
        
            return back()->with( 'positive-message', 'Evento supposto creato.');
        }
        return 1;
    }

    public static function set_communication( Request $request ) {
        if( $request->has('communication')) {
            $comm = $request->input( 'communication', '' );
            Settings::updoption( 'communication', $comm );
            Log::info("Updated communication", Logger::logParams(['text' => $comm ] ) );
        }
        if( $request->has('communication_priv') ) {
            $comm = $request->input( 'communication_priv', '' );
            Settings::updoption( 'communication_private', $comm );
            Log::info("Updated communication private", Logger::logParams(['text' => $comm ] ) );
        }
        return back()->with( 'positive-message', 'Aggiornato' );
    }
}
