<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'birthday',
        'isadmin'
    ];

    protected $appends = [
        'is_alive',
        'is_team_boss'
    ];

    protected $cast = [
        'isadmin' => 'boolean'
    ];

    public function events_done() {
        return $this->hasMany( Event::class, 'actor' );
    }

    public function events_suffered() {
        return $this->hasMany( Event::class, 'target' );
    }

    public function getIsAliveAttribute() {
        $last_event = $this->events_suffered()->latest()->get();
        if( $last_event->count() )
            return (bool) $last_event[0]->finalstate;
        return True;
    }
    
    public function getIsTeamBossAttribute() {
        $theteam = $this->theteam;
        if( !is_null( $theteam ) && $theteam == $this->bossof )
            return True;
        else return False;
    }

    public static function admin() {
        return Auth::check() && Auth::user()->isadmin;
    }
    
    public function bossof() {
        return $this->hasOne( Team::class, 'boss' );
    }
    
    public function theteam() {
        return $this->belongsTo( Team::class, 'team' );
    }
}
