<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Climate extends Model
{
    public $timestamps = false;

    protected $fillable = ['destination_id', 'month', 'avg_min', 'avg_max'];

    protected $casts = [
        'month' => 'integer',
        'avg_min' => 'float',
        'avg_max' => 'float',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
