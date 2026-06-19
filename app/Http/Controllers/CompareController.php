<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Services\DestinationSearchService;
use App\Services\RecommendationService;
use App\Services\VacationCatalog;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(
        Request $request,
        DestinationSearchService $searchService,
        RecommendationService $recommendations
    )
    {
        $ids = collect((array) $request->query('ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();
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

        $destinations = Destination::query()
            ->whereIn('id', $ids)
            ->with(['climate' => fn ($query) => $query->whereIn('month', $selectedMonths)->orderBy('month')])
            ->get()
            ->each(fn ($destination) => $destination->setRelation('selectedClimate', $searchService->aggregateClimate($destination->climate)))
            ->sortBy(fn ($destination) => $ids->search($destination->id))
            ->values();

        return view('compare', [
            'destinations' => $destinations,
            'month' => $month,
            'selectedMonths' => $selectedMonths,
            'selectedMonthLabel' => $searchService->monthLabel($selectedMonths),
            'months' => VacationCatalog::months(),
            'recommendations' => $recommendations,
        ]);
    }
}
