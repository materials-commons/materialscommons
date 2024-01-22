<?php

namespace App\Http\Controllers\Published\Datasets;

use App\Actions\Published\Datasets\SearchPublishedDatasetAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;
use function route;
use function trim;
use function view;

class SearchPublishedDatasetWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, SearchPublishedDatasetAction $searchPublishedDatasetAction,
                             Dataset $dataset)
    {
        $search = trim($request->input('search'));
        if ($search == "") {
            return "";
        }

        $searchResults = $searchPublishedDatasetAction->execute($search, $dataset->id);

        return view('partials._htmx_search', [
            'searchResults' => $searchResults,
            'search'        => $search,
            'searchRoute'   => route('public.datasets.search', [$dataset, 'search' => '']),
        ]);
    }
}
