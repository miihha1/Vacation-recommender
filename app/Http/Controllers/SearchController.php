<?php

namespace App\Http\Controllers;

use App\Services\DestinationSearchService;
use App\Services\VacationCatalog;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private readonly DestinationSearchService $searchService)
    {
    }

    public function index(Request $request)
    {
        $search = $this->searchService->normalize($request->query());
        $hasSearch = $request->has('types');
        $results = $hasSearch ? $this->searchService->search($search) : collect();

        return view('search', $this->viewData($search, $results, $hasSearch));
    }

    public function search(Request $request)
    {
        $search = $this->searchService->normalize($request->all());
        $results = $this->searchService->search($search);

        return view('search', $this->viewData($search, $results, true));
    }

    private function viewData(array $search, $results, bool $hasSearch): array
    {
        return [
            'months' => VacationCatalog::months(),
            'types' => VacationCatalog::types(),
            'temperatures' => VacationCatalog::temperatures(),
            'distances' => VacationCatalog::distances(),
            'search' => $search,
            'selectedMonth' => $this->searchService->selectedMonth($search),
            'selectedMonths' => $this->searchService->selectedMonths($search),
            'selectedMonthLabel' => $this->searchService->monthLabel($this->searchService->selectedMonths($search)),
            'results' => $results,
            'hasSearch' => $hasSearch,
        ];
    }
}
