<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class Settings extends Controller
{
    

    private static $thedata = array();

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
