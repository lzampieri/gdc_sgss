<?php

namespace App\Logging;

use App\Http\Controllers\Settings;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TelegramLogger {

    const ResponseMessages = [
        '200' => 'Message has been sent.',
        '400' => 'Chat ID is not valid.',
        '401' => 'Bot Token is not valid.',
        '404' => 'Bot Token is not valid.',
    ];

    public static function sendInfoMessage( $messageText ) {
        TelegramLogger::sendMessage( "&#129348; " . $messageText );
    }

    public static function sendEmergencyMessage( $messageText ) {
        TelegramLogger::sendMessage( "&#9888;&#65039;&#9888;&#65039;&#9888;&#65039; ATTENZIONE &#9888;&#65039;&#9888;&#65039;&#9888;&#65039;\n" . $messageText );
    }

    public static function sendMessage( $messageText )
    {
        $token   = env( 'TELEGRAM_BOT_TOKEN' );
        $chat_id = env( 'TELEGRAM_CHAT_ID' );
        $url   = "https://api.telegram.org/bot" . $token . "/sendMessage";
        $query = http_build_query([
            'text' => $messageText,
            'chat_id' => $chat_id,
            'parse_mode' => 'html',
        ]);

        $responseHeader = get_headers( $url . "?" . $query );
        $responseStatusCode = substr($responseHeader[0], 9, 3);
        $responseMessage = TelegramLogger::ResponseMessages[ $responseStatusCode ];

        Log::info("Telegram logger: " . $responseMessage);
    }

    public static function registration( User $user ) {
        if( Settings::obtain( 'notify_registrations' ) == 1 ) {
            TelegramLogger::sendInfoMessage( $user->name . " iscritto." );
        }
    }

    public static function event_created( Event $event ) {
        $target_name = $event->thetarget->name;
        $actor_name = $event->theactor->name;
        $type = $event->finalstate ? "Resurrezione" : "Morte";

        TelegramLogger::sendInfoMessage( $type . " di " . $target_name . " a mano di " . $actor_name );
    }

    public static function cronjobs( $log ) {
        TelegramLogger::sendInfoMessage( "Sono stati svolti i seguenti lavori programmati:\n" . $log );
    }
}