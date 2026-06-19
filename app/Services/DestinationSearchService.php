<?php

namespace App\Services;

use App\Models\Destination;
use App\Models\SearchLog;
use Illuminate\Support\Collection;

class DestinationSearchService
{
    public function normalize(array $input): array
    {
        $travelMode = (string) ($input['travel_mode'] ?? 'month');
        $temperature = (string) ($input['temperature'] ?? 'any');
        $distance = (string) ($input['distance'] ?? 'any');
        $types = array_values(array_intersect(array_keys(VacationCatalog::types()), (array) ($input['types'] ?? [])));

        return [
            'travel_mode' => in_array($travelMode, ['month', 'range'], true) ? $travelMode : 'month',
            'month' => max(1, min(12, (int) ($input['month'] ?? now()->month))),
            'date_from' => trim((string) ($input['date_from'] ?? '')),
            'date_to' => trim((string) ($input['date_to'] ?? '')),
            'days' => max(1, min(90, (int) ($input['days'] ?? 7))),
            'types' => $types ?: ['beach'],
            'temperature' => array_key_exists($temperature, VacationCatalog::temperatures()) ? $temperature : 'any',
            'distance' => array_key_exists($distance, VacationCatalog::distances()) ? $distance : 'any',
        ];
    }

    public function selectedMonth(array $search): int
    {
        return $this->selectedMonths($search)[0];
    }

    public function selectedMonths(array $search): array
    {
        if ($search['travel_mode'] !== 'range'
            || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $search['date_from'])
            || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $search['date_to'])) {
            return [(int) $search['month']];
        }

        $from = strtotime($search['date_from']);
        $to = strtotime($search['date_to']);
        if ($from === false || $to === false) {
            return [(int) $search['month']];
        }

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $months = [];
        $cursor = strtotime(date('Y-m-01', $from));
        $end = strtotime(date('Y-m-01', $to));

        while ($cursor <= $end) {
            $months[] = (int) date('n', $cursor);
            $cursor = strtotime('+1 month', $cursor);
        }

        return array_values(array_unique($months));
    }

    public function monthLabel(array $months): string
    {
        $labels = VacationCatalog::months();
        $names = array_map(fn (int $month) => $labels[$month], $months);

        return implode(', ', $names);
    }

    public function search(array $search, bool $log = true): Collection
    {
        $months = $this->selectedMonths($search);
        $month = $months[0];
        $destinations = Destination::query()
            ->with(['climate' => fn ($query) => $query->whereIn('month', $months)->orderBy('month')])
            ->whereHas('climate', fn ($query) => $query->whereIn('month', $months))
            ->get();

        $results = $destinations->map(function (Destination $destination) use ($search) {
            $climate = $this->aggregateClimate($destination->climate);
            $types = $destination->typeList();
            $matchingTypes = array_values(array_intersect($search['types'], $types));
            if ($climate === null || $matchingTypes === []) {
                return null;
            }

            $avg = ($climate->avg_min + $climate->avg_max) / 2;
            if ($search['temperature'] === 'hot' && $climate->avg_max < 30) {
                return null;
            }
            if ($search['temperature'] === 'warm' && ($avg < 20 || $avg >= 30)) {
                return null;
            }
            if ($search['temperature'] === 'mild' && ($avg < 10 || $avg >= 20)) {
                return null;
            }
            if ($search['distance'] === 'short' && $destination->flight_hours > 3) {
                return null;
            }
            if ($search['distance'] === 'medium' && $destination->flight_hours > 5) {
                return null;
            }

            $matchScore = count($matchingTypes) * 25;
            $reasons = [
                'spĺňa typ dovolenky: ' . implode(', ', VacationCatalog::typeLabels($matchingTypes)),
            ];

            if ($search['temperature'] === 'any') {
                $matchScore += 12;
                $reasons[] = 'teplotu ponechávate otvorenú, preto ju neobmedzuje';
            } else {
                $matchScore += 22;
                $reasons[] = 'počasie zodpovedá voľbe ' . VacationCatalog::temperatures()[$search['temperature']];
            }

            if ($search['distance'] === 'any') {
                $matchScore += 8;
                $reasons[] = 'vzdialenosť nie je limitujúca';
            } else {
                $target = $search['distance'] === 'short' ? 2.2 : 4.0;
                $matchScore += max(0, 18 - abs($destination->flight_hours - $target) * 3);
                $reasons[] = 'let trvá približne ' . number_format($destination->flight_hours, 1, ',', ' ') . ' h';
            }

            $matchScore += max(0, 12 - $destination->flight_hours * 2);

            $destination->setAttribute('match_score', round($matchScore, 1));
            $destination->setAttribute('reasons', $reasons);
            $destination->setRelation('selectedClimate', $climate);

            return $destination;
        })->filter()
            ->sortBy([
                ['match_score', 'desc'],
                ['flight_hours', 'asc'],
                ['name', 'asc'],
            ])
            ->values();

        if ($log) {
            $results->take(8)->each(function (Destination $destination) use ($search, $month): void {
                SearchLog::create([
                    'destination_id' => $destination->id,
                    'travel_month' => $month,
                    'days_count' => $search['days'],
                    'types' => implode(',', $search['types']),
                    'temperature_pref' => $search['temperature'],
                    'distance_pref' => $search['distance'],
                    'searched_at' => now(),
                ]);
            });
        }

        return $results;
    }

    public function aggregateClimate(Collection $climates): ?object
    {
        if ($climates->isEmpty()) {
            return null;
        }

        return (object) [
            'avg_min' => round((float) $climates->avg('avg_min'), 1),
            'avg_max' => round((float) $climates->avg('avg_max'), 1),
            'months' => $climates->pluck('month')->map(fn ($month) => (int) $month)->values()->all(),
        ];
    }
}
