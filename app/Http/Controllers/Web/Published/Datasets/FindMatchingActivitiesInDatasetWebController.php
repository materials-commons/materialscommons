<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Actions\Activities\FindActivitiesByNameAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class FindMatchingActivitiesInDatasetWebController extends Controller
{
    public function __invoke(Request $request, FindActivitiesByNameAction $findActivitiesByNameAction, Dataset $dataset)
    {
        $search = $request->input('search');
        $searchResults = $findActivitiesByNameAction->limitToExperiment($dataset->id)->execute($search);

        return view('', compact('searchResults', 'search'));
    }
}
