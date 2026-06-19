<?php

namespace App\Services;

use App\Models\Airport;
use App\Models\Destination;

class AirportService
{
    public function nearest(Destination $destination): ?object
    {
        return Airport::query()
            ->selectRaw(
                '*, (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance_km',
                [$destination->latitude, $destination->longitude, $destination->latitude]
            )
            ->orderBy('distance_km')
            ->first();
    }
}
