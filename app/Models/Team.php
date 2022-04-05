<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    public function theboss() {
        return $this->belongsTo( User::class, 'boss' );
    }
    
    public function users() {
        return $this->hasMany( User::class, 'team' );
    }

    public function anyAlive() {
        return $this->usersAlive()->count() > 0;
    }
    
    public function usersAlive() {
        return $this->users->filter( function ( $u ) { return $u->is_alive; } );
    }

    public function stringify() {
        $users = $this->users()->get()->filter( function ( $u ) { return $u->is_alive; } );
        $string = "";
        foreach ( $users as $u ) {
            $string .= $u->name . ", ";
        }
        return rtrim($string, ", ");
    }
}