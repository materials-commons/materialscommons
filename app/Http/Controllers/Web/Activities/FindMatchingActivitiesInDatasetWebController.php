<?php

namespace App\Http\Controllers\Web\Activities;

use App\Actions\Activities\FindActivitiesByNameAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Http\Request;

class FindMatchingActivitiesInDatasetWebController extends Controller
{
    public function __invoke(Request $request, FindActivitiesByNameAction $findActivitiesByNameAction, Project $project, Dataset $dataset)
    {
        $search = $request->input('search');
        $searchResults = $findActivitiesByNameAction->limitToDataset($dataset->id)->execute($search);

        return view('', compact('project', 'searchResults', 'search'));
    }
}
