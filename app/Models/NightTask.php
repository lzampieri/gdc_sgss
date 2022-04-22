<?php

namespace App\Models;

use App\Http\Controllers\Settings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NightTask extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'action_type',
        'action_params',
        'passcode'
    ];
    
    protected $casts = [
        'action_params' => 'array'
    ];

    public function thepasscode() {
        return $this->belongsTo( NightTaskPasscode::class, 'passcode' );
    } 

    public function explain() {
        if( $this->action_type == 'change_option' ) {
            foreach( Settings::editable as $s ) {
                if( $this->action_params['option_name'] == $s['name'] ) {
                    foreach( $s['dict'] as $k => $v ) {
                        if( $this->action_params['option_value'] == $k ) {
                            return "Cambio opzione \"" . $s['title'] . "\" a \"" . $v . "\"";
                        }
                    }
                }
            }
        }
        else return "Sconosciuta";
    }

}