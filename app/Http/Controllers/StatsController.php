<?php

namespace App\Http\Controllers;

use App\Models\SearchLog;
use App\Models\Visit;
use App\Services\VacationCatalog;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        $total = Visit::count();
        $unique = Visit::where('visited_at', '>=', now()->subMinutes(60))->distinct('visitor_hash')->count('visitor_hash');

        $timeRows = Visit::query()
            ->selectRaw(
                "CASE
                    WHEN TIME(visited_at) >= '06:00:00' AND TIME(visited_at) < '15:00:00' THEN '06:00-15:00'
                    WHEN TIME(visited_at) >= '15:00:00' AND TIME(visited_at) < '21:00:00' THEN '15:00-21:00'
                    WHEN TIME(visited_at) >= '21:00:00' THEN '21:00-24:00'
                    ELSE '00:00-06:00'
                END AS bucket, COUNT(*) AS visits"
            )
            ->groupBy('bucket')
            ->get();

        $searched = SearchLog::query()
            ->join('destinations', 'destinations.id', '=', 'search_logs.destination_id')
            ->select('destinations.name', 'destinations.country_name', DB::raw('COUNT(*) AS cnt'))
            ->groupBy('destinations.id', 'destinations.name', 'destinations.country_name')
            ->orderBy('destinations.country_name')
            ->orderBy('destinations.name')
            ->get();

        $prefs = SearchLog::query()
            ->select('temperature_pref', DB::raw('COUNT(*) AS cnt'))
            ->groupBy('temperature_pref')
            ->orderByDesc('cnt')
            ->get();

        $typeCounts = array_fill_keys(array_keys(VacationCatalog::types()), 0);
        SearchLog::query()->pluck('types')->each(function (string $types) use (&$typeCounts): void {
            foreach (explode(',', $types) as $type) {
                if (array_key_exists($type, $typeCounts)) {
                    $typeCounts[$type]++;
                }
            }
        });

        return view('stats', compact('total', 'unique', 'timeRows', 'searched', 'prefs', 'typeCounts'));
    }
}
