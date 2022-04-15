<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PendingKill;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\PseudoTypes\False_;

class Pendings extends Controller
{
    public static function create( $userId ) {
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
        
        $pendingKill->delete();

        $resuscitation = Pendings::resuscitate( $event );
        
        if( $resuscitation )
            return $resuscitation;
        
        return back()->with( 'positive-message', 'Omicidio confermato.');
    }

    public static function resuscitate( $event ) {
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

        if( count( $papables ) < 1 )
            return false;
        
        $papables->sort( function ($a, $b) { 
            if( $a->death_time()->lt( $b->death_time() ) ) return -1;
            else return 1;
        });

        $event = Event::create([
            'actor' => $event->actor,
            'target' => $papables[0]->id,
            'finalstate' => True,
            'created_at' => $event->updated_at
        ]);

        Mailer::event_created( $event );
        
        return back()->with( 'positive-message', 'Omicidio confermato. Questo omicidio ha portato alla resurrezione di ' . $papables[0]->name );
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
        
        return back()->with( 'positive-message', 'Omicidio annullato.');
    }
}
