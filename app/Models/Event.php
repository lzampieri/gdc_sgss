<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

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

    public function actor() {
        return $this->belongsTo( User::class, 'actor' );
    }
    
    public function target() {
        return $this->belongsTo( User::class, 'target' );
    }
}