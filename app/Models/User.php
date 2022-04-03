<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'isadmin'
    ];

    protected $appends = [
        'isalive'
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

    public function getIsaliveAttribute() {
        $last_event = $this->events_suffered()->latest()->get();
        if( $last_event->count() )
            return (bool) $last_event[0]->finalstate;
        return True;
    }

    public static function admin() {
        return Auth::check() && Auth::user()->isadmin;
    }
}
