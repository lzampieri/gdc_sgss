<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'actor',
        'target',
        'finalstate',
    ];

    protected $cast = [
        'finalstate' => 'boolean'
    ];

    public function theactor() {
        return $this->belongsTo( User::class, 'actor' );
    }
    
    public function thetarget() {
        return $this->belongsTo( User::class, 'target' );
    }
}