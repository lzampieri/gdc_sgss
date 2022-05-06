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
        if( $this->action_type == 'shuffle_cycle' ) {
            foreach( Settings::cycles as $s ) {
                if( $this->action_params['cycle_name'] == $s['name'] ) {
                    return "Shuffle del ciclo  " . $s['title'];
                }
            }
        }
        if( $this->action_type == 'add_event' ) {
            $dict = [ -1 => 'Morte presunta', 0 => 'Morte', 1 => 'Resurrezione' ];
            return $dict[ $this->action_params['finalState'] ] . " di " . User::find( $this->action_params['target'] )->name . " a mano di " . User::find( $this->action_params['actor'] )->name . ( $this->action_params['sendmail'] ? " con" : " senza" ) . " spedizione di mail e " . ( $this->action_params['resurrections'] ? "con" : "senza" ) . " eventuali resurrezioni.";
        }
        else return "Sconosciuta";
    }

}