<?php

namespace App\Http\Controllers;

use App\Logging\Logger;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Settings extends Controller
{
    private static $thedata = array();

    const editable = [
        [
            'name' => 'signup_enabled',
            'title' => 'Iscrizioni',
            'dict' => [
                '0' => 'Chiuse',
                '1' => 'Aperte'
            ],
            'default' => '0'
        ],
        [
            'name' => 'kills_enabled',
            'title' => 'Uccisioni',
            'dict' => [
                '0' => 'Chiuse',
                '1' => 'Aperte'
            ],
            'default' => '0'
        ],
        [
            'name' => 'team_visible',
            'title' => 'Propria squadra',
            'dict' => [
                '0' => 'Nascosta',
                '1' => 'Visible'
            ],
            'default' => '0'
        ],
        [
            'name' => 'show_rival_boss',
            'title' => 'Caposquadra degli avversari',
            'dict' => [
                '0' => 'Nascosto',
                '1' => 'Visible'
            ],
            'default' => '0'
        ],
        [
            'name' => 'edit_team_boss',
            'title' => 'Cambio capo squadra',
            'dict' => [
                '0' => 'Bloccato',
                '1' => 'Permesso'
            ],
            'default' => '0'
        ],
        [
            'name' => 'method',
            'title' => 'Metodo di ciclazione',
            'dict' => [
                'disabled' => 'Nessun obiettivo',
                'single_single' => 'Singolo ciclo, singolo obiettivo',
                'single_double' => 'Singolo ciclo, obiettivo seguente e successivo',
                'teams_single_single' => 'Singolo ciclo, singola squadra come obiettivo, squadre'
            ],
            'default' => 'disabled'
        ],
        [
            'name' => 'jesus_boss',
            'title' => 'Caposquadra con poteri apotropaici',
            'dict' => [
                'disabled' => 'Disabilitato',
                '24' => 'Su uccisione, entro 24h, di un qualsiasi obiettivo',
                '36' => 'Su uccisione, entro 36h, di un qualsiasi obiettivo',
                '48' => 'Su uccisione, entro 48h, di un qualsiasi obiettivo',
            ],
            'default' => 'disabled'
        ]
    ];

    const cycles = [
        [
            'name' => 'single_cycle',
            'title' => 'Ciclo singoli',
            'default' => '[]'
        ],
        [
            'name' => 'teams_cycle',
            'title' => 'Ciclo squadre',
            'default' => '[]'
        ]
    ];

    const reserved = [
        [
            'name' => 'communication',
            'default' => ''
        ],
        [
            'name' => 'communication_private',
            'default' => ''
        ]
    ];

    public static function load_static() {
        if( count( Settings::$thedata ) == 0 ) {
            Setting::all()->each(
                function ($item) { Settings::$thedata += [ $item['key'] => $item['value'] ]; }
                );
        }
    }

    public static function obtain( $key ) {
        Settings::load_static();
        return Settings::$thedata[ $key ];
    }

    public static function obtain_all() {
        Settings::load_static();
        return Settings::$thedata;
    }

    public static function updoption( $key, $value ) {
        $setting = Setting::updateOrCreate( [ 'key' => $key ], [ 'value' => $value ] );
        Log::info("Settings updated", Logger::logParams(['setting' => $setting] ) );
        return back();
    }

    public static function ensure() {
        foreach ( Settings::editable as $s ) {
            Setting::firstOrCreate( [ 'key' => $s['name'] ], [ 'value' => $s['default'] ] );
        }
        foreach ( Settings::cycles as $s ) {
            Setting::firstOrCreate( [ 'key' => $s['name'] ], [ 'value' => $s['default'] ] );
        }
        foreach ( Settings::reserved as $s ) {
            Setting::firstOrCreate( [ 'key' => $s['name'] ], [ 'value' => $s['default'] ] );
        }
        return redirect()->route('home')->with('positive-message','Fatto');
    }

    public static function migrate() {
        Artisan::call('migrate', [ '--force' => true ] );
        Settings::ensure();
        return redirect()->route('home')->with('positive-message','Fatto');
    }
}
