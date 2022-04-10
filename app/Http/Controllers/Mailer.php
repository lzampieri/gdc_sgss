<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Models\PendingKill;

class Mailer extends Controller
{

    public static function welcome_mail( ) {
        $uname = Auth::user()->name;
        mail(
            Auth::user()->email,
            'Sei nel gioco',
            <<<TXT
                Ciao $uname!
                Ti sei iscritto con successo al Gioco del Cucchiaino, edizione 2022.
                Per accedere alla tua pagina privata, dalla quale potrai controllare i tuoi obiettivi e dichiarare le uccisioni, devi effettuare il login all'indirizzo:
                    http://gdcsgss.x10.mx/
                Non dimenticare di tenere controllata questa casella di posta in attesa di novità.
                Che lo spirito di Michele Cortelazzo ti accompagni lungo questa avventura.
                Mors Vobiscum
                mors.vobiscum@gmail.com
            TXT,
            env( 'MAIL_HEADERS' )
        );
    }

    public static function pending_create( PendingKill $pending ) {
        $target_name = $pending->thetarget->name;
        $actor_name = $pending->theactor->name;
        $approve_route = route('pending.approve', [ 'claimId' => $pending->id ] );
        $reject_route = route('pending.reject', [ 'claimId' => $pending->id ] );
        $home_route = route('home');

        mail(
            $pending->thetarget->email,
            'Sei morto?',
            <<<TXT
                Ciao $target_name!
                $actor_name afferma di averti ucciso.
                Clicca <a href="$approve_route">qui</a> per confermare, oppure <a href="$reject_route">qui</a> se si tratta di una barbara menzogna.
                Se i link non funzionano, è sufficiente accedere al <a href="$home_route">sito</a> per confermare o rinnegare.
                Si raccomanda particolare celerità e onestà.
                Mors Vobiscum
                mors.vobiscum@gmail.com
            TXT,
            env( 'MAIL_HEADERS' )
        );
    }

    public static function pending_approved( Event $event ) {
        $target_name = $event->thetarget->name;
        $actor_name = $event->theactor->name;

        mail(
            env( 'MAIL_LIST' ),
            'Notifica di morte',
            <<<TXT
                Con la presente a notificare la morte di $target_name a mano di $actor_name.
                L'amministrazione.
            TXT,
            env( 'MAIL_HEADERS' )
        );
    }
}

