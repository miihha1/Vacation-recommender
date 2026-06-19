<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    protected $fillable = [
        'name',
        'country_code',
        'country_name',
        'capital',
        'currency_code',
        'currency_name',
        'types',
        'flight_hours',
        'latitude',
        'longitude',
        'image_url',
        'summary',
    ];

    protected $casts = [
        'flight_hours' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function climate(): HasMany
    {
        return $this->hasMany(Climate::class);
    }

    public function searches(): HasMany
    {
        return $this->hasMany(SearchLog::class);
    }

    public function typeList(): array
    {
        return array_filter(explode(',', $this->types));
    }
}
