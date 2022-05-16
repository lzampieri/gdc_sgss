<?php

namespace App\Http\Controllers;

use App\Logging\Logger;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Models\PendingKill;
use Illuminate\Support\Facades\Log;

class Mailer extends Controller
{

    public static function welcome_mail( ) {
        $uname = Auth::user()->name;
        $mail = Auth::user()->email;
        mail(
            $mail,
            'Sei nel gioco',
            <<<TXT
                Ciao $uname!
                Ti sei iscritto con successo al Gioco del Cucchiaino.
                Per accedere alla tua pagina privata, dalla quale potrai controllare i tuoi obiettivi e dichiarare le uccisioni, devi effettuare il login all'indirizzo:
                    http://gdcsgss.x10.mx/
                Non dimenticare di tenere controllata questa casella di posta in attesa di novità.
                Che lo spirito di Michele Cortelazzo ti accompagni lungo questa avventura.
                Mors Vobiscum
                mors.vobiscum@gmail.com
            TXT,
            env( 'MAIL_HEADERS' )
        );
        Log::info("Send welcome mail", Logger::logParams(['to' => $mail] ) );
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
        Log::info("Send pending event mail", Logger::logParams(['to' => $pending->thetarget->email] ) );
    }

    public static function event_created( Event $event ) {
        $target_name = $event->thetarget->name;
        $actor_name = $event->theactor->name;
        $target_email = $event->thetarget->email;
        $actor_email = $event->theactor->email;
        $type = $event->finalstate ? "resurrezione" : "morte";
        $mail = $target_email . ', ' . $actor_email;
        // $mail .= ', ' . env( 'MAIL_LIST' );

        mail(
            $mail,
            'Notifica di ' . $type,
            <<<TXT
                Con la presente a notificare la $type di $target_name a mano di $actor_name.
                L'amministrazione.
            TXT,
            env( 'MAIL_HEADERS' )
        );
        Log::info("Send event created mail", Logger::logParams(['to' => $mail] ) );

        // Logger::telegramInfo(<<<TXT
        //     $type di $target_name a mano di $actor_name.
        // TXT);
    }

    // public static function cronjobs( $log ) {
    //     Logger::telegramInfo(<<<TXT
    //             Sono stati svolti i seguenti lavori programmati:
    //             $log
    //     TXT);
    //     Log::info("Send cronjob message", Logger::logParams([]) );

    //     $mail = env( 'MAIL_LIST' );

    //     mail(
    //         $mail,
    //         'Lavori programmati',
    //         <<<TXT
    //             Sono stati svolti i seguenti lavori programmati:
    //             $log
    //             L'amministrazione.
    //         TXT,
    //         env( 'MAIL_HEADERS' )
    //     );
    //     Log::info("Send cronjob mail", Logger::logParams(['to' => $mail] ) );
    // }
}

