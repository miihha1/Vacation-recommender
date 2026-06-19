<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'destination_id',
        'travel_month',
        'days_count',
        'types',
        'temperature_pref',
        'distance_pref',
        'searched_at',
    ];

    protected $casts = [
        'travel_month' => 'integer',
        'days_count' => 'integer',
        'searched_at' => 'datetime',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
