<?php

namespace App\Http\Controllers;

use App\Logging\Logger;
use App\Models\Event;
use App\Models\PendingKill;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\PseudoTypes\False_;

class Pendings extends Controller
{
    public static function create( $userId ) {
        if(( Settings::obtain( 'kills_enabled' ) == 0 ) )
            return back()->with( 'negative-message', 'Errore: in questo momento non è permesso uccidere.');

        $target = User::find( $userId );
        if( $target == null ) {
            return back()->with( 'negative-message', 'Errore: obiettivo non trovato.');
        }
        $actor = Auth::user();
        
        $pendingKill = PendingKill::create([
            'actor' => $actor->id,
            'target' => $target->id
        ]);

        Mailer::pending_create( $pendingKill );
        
        Log::info("Created pending event", Logger::logParams(['pending_event' => $pendingKill] ) );

        return back()->with( 'positive-message', 'Una mail è stata inviata alla presunta vittima. Attendi che confermi di essere stata davvero uccisa.');
    }

    public static function approve( $claimId ) {
        $pendingKill = PendingKill::find( $claimId );
        if( $pendingKill == null ) {
            return back()->with( 'negative-message', 'Errore: questa uccisione è già stata confermata o annullata.');
        }
        if( ( $pendingKill->target != Auth::user()->id ) && !( Auth::user()->isadmin ) ) {
            return back()->with( 'negative-message', 'Errore: non hai il permesso di confermare questa uccisione.');
        }
        
        $event = Event::create([
            'actor' => $pendingKill->actor,
            'target' => $pendingKill->target,
            'finalstate' => False,
            'created_at' => $pendingKill->created_at
        ]);

        Mailer::event_created( $event );

        Log::info("Created event from pending approvation", Logger::logParams(['event' => $event] ) );
        
        $pendingKill->delete();

        $resuscitation = Pendings::resuscitate( $event );
        
        if( $resuscitation )
            return $resuscitation;
        
        return back()->with( 'positive-message', 'Omicidio confermato.');
    }

    public static function resuscitate( $event, $send_mail = True ) {
        $actor = $event->theactor;
        if( !( $actor->is_team_boss ) )
            return false;

        if( Settings::obtain('jesus_boss') == 'disabled' ) {
            return false;
        }

        $hours = Settings::obtain('jesus_boss');
        $must_be_dead_time = $event->created_at;
        $must_be_alive_time = $event->created_at->subHours( $hours );

        $papables = $actor->theteam->users->filter(
            function ($u) use ($must_be_alive_time, $must_be_dead_time) {
                if( $u->is_alive )
                    return false;
                $death_time = $u->death_time();
                if( $death_time->gt( $must_be_dead_time ) )
                    return false;
                if( $death_time->lt( $must_be_alive_time ) )
                    return false;
                return true;
            }
        );

        if( $papables->count() < 1 )
            return false;

        $papables->sort( function ($a, $b) { 
            if( $a->death_time()->lt( $b->death_time() ) ) return -1;
            else return 1;
        });

        $event = Event::create([
            'actor' => $event->actor,
            'target' => $papables->first()->id,
            'finalstate' => True,
            'created_at' => $event->updated_at
        ]);

        if( $send_mail ) {
            Mailer::event_created( $event );
        }
        
        Log::info("Created event from automatic resurrection", Logger::logParams(['event' => $event] ) );
        
        return back()->with( 'positive-message', 'Omicidio confermato. Questo omicidio ha portato alla resurrezione di ' . $papables->first()->name );
    }
    
    public static function reject( $claimId ) {
        $pendingKill = PendingKill::find( $claimId );
        if( $pendingKill == null ) {
            return back()->with( 'negative-message', 'Errore: questa uccisione è già stata confermata o annullata.');
        }
        if( ($pendingKill->actor != Auth::user()->id) && ($pendingKill->target != Auth::user()->id) && !( Auth::user()->isadmin ) ) {
            return back()->with( 'negative-message', 'Errore: non hai il permesso di annullare questa uccisione.');
        }
        
        $pendingKill->delete();

        Log::info("Pending kill rejected", Logger::logParams(['pending_event' => $pendingKill] ) );
        
        return back()->with( 'positive-message', 'Omicidio annullato.');
    }
}
