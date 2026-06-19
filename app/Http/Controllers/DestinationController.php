<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Destination;
use App\Services\AirportService;
use App\Services\DestinationSearchService;
use App\Services\ExternalDataService;
use App\Services\RecommendationService;
use App\Services\VacationCatalog;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function show(
        Request $request,
        AirportService $airports,
        DestinationSearchService $searchService,
        ExternalDataService $external,
        RecommendationService $recommendations
    ) {
        $destination = Destination::findOrFail((int) $request->query('id'));
        $selectedMonths = collect((array) $request->query('months', []))
            ->map(fn ($month) => max(1, min(12, (int) $month)))
            ->filter()
            ->unique()
            ->values()
            ->all();
        if ($selectedMonths === []) {
            $selectedMonths = [max(1, min(12, (int) $request->query('month', now()->month)))];
        }
        $month = $selectedMonths[0];
        $climate = $destination->climate()->whereIn('month', $selectedMonths)->orderBy('month')->get();
        $selectedClimate = $searchService->aggregateClimate($climate) ?? $destination->climate()->orderBy('month')->first();

        return view('destinations.show', [
            'destination' => $destination,
            'month' => $month,
            'selectedMonths' => $selectedMonths,
            'selectedMonthLabel' => $searchService->monthLabel($selectedMonths),
            'months' => VacationCatalog::months(),
            'climate' => $climate,
            'selectedClimate' => $selectedClimate,
            'country' => Country::find($destination->country_code),
            'airport' => $airports->nearest($destination),
            'rate' => $external->exchangeRateToEuro($destination->currency_code),
            'forecast' => $external->forecast($destination),
            'recommendation' => $selectedClimate ? $recommendations->text($destination, $selectedClimate) : null,
        ]);
    }
}
