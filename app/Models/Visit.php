<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    public $timestamps = false;

    protected $fillable = ['visitor_hash', 'user_agent', 'visited_at'];

    protected $casts = [
        'visited_at' => 'datetime',
    ];
}
