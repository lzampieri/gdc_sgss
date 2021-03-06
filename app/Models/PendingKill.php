<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PendingKill extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'actor',
        'target'
    ];

    public function theactor() {
        return $this->belongsTo( User::class, 'actor' );
    }
    
    public function thetarget() {
        return $this->belongsTo( User::class, 'target' );
    }
}
