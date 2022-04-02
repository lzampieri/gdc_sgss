<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

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
}
