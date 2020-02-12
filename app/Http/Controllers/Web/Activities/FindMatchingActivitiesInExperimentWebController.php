<?php

namespace App\Http\Controllers\Web\Activities;

use App\Actions\Activities\FindActivitiesByNameAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;

class FindMatchingActivitiesInExperimentWebController extends Controller
{
    public function __invoke(Request $request, FindActivitiesByNameAction $findActivitiesByNameAction, Project $project, Experiment $experiment)
    {
        $search = $request->input('search');
        $searchResults = $findActivitiesByNameAction->limitToExperiment($experiment->id)->execute($search);

        return view('', compact('project', 'searchResults', 'search'));
    }
}
