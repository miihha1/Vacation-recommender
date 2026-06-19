<?php

namespace App\Services;

use App\Models\Destination;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExternalDataService
{
    public function exchangeRateToEuro(string $currency): ?float
    {
        if ($currency === 'EUR') {
            return null;
        }

        return Cache::remember("rate-eur-{$currency}", now()->addHour(), function () use ($currency) {
            $response = Http::timeout(4)->get('https://api.frankfurter.app/latest', [
                'from' => 'EUR',
                'to' => $currency,
            ]);

            if (!$response->ok()) {
                return null;
            }

            return (float) data_get($response->json(), "rates.{$currency}") ?: null;
        });
    }

    public function forecast(Destination $destination): ?array
    {
        return Cache::remember("forecast-{$destination->id}", now()->addMinutes(45), function () use ($destination) {
            $response = Http::timeout(4)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $destination->latitude,
                'longitude' => $destination->longitude,
                'current' => 'temperature_2m,precipitation,weather_code',
                'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_probability_max',
                'forecast_days' => 3,
                'timezone' => 'auto',
            ]);

            return $response->ok() ? $response->json() : null;
        });
    }
}
