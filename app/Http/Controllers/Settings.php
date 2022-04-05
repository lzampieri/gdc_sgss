<?php

namespace App\Http\Controllers;

use App\Models\Setting;

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
            ]
        ],
        [
            'name' => 'team_visible',
            'title' => 'Propria squadra',
            'dict' => [
                '0' => 'Nascosta',
                '1' => 'Visible'
            ]
        ],
        [
            'name' => 'edit_team_boss',
            'title' => 'Cambio capo squadra',
            'dict' => [
                '0' => 'Bloccato',
                '1' => 'Permesso'
            ]
        ],
        [
            'name' => 'method',
            'title' => 'Metodo di ciclazione',
            'dict' => [
                'disabled' => 'Nessun obiettivo',
                'single_single' => 'Singolo ciclo, singolo obiettivo',
                'single_double' => 'Singolo ciclo, obiettivo seguente e successivo',
                'teams_single_single' => 'Singolo ciclo, singola squadra come obiettivo, squadre'
            ]
        ],
    ];

    const reserved = [
        [
            'name' => 'single_cycle'
        ],
        [
            'name' => 'teams_cycle'
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

    public function updoption( $key, $value ) {
        Setting::updateOrCreate( [ 'key' => $key ], [ 'value' => $value ] );
        return back();
    }
}
