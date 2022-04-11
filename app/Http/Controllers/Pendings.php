<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PendingKill;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        
        return back()->with( 'positive-message', 'Omicidio confermato.');
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
