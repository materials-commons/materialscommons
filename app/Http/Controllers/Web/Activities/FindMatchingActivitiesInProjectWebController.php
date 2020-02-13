<?php

namespace App\Http\Controllers\Web\Activities;

use App\Actions\Activities\FindActivitiesByNameAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class FindMatchingActivitiesInProjectWebController extends Controller
{
    public function __invoke(Request $request, FindActivitiesByNameAction $findActivitiesByNameAction, Project $project)
    {
        $search = $request->input('search');
        $searchResults = $findActivitiesByNameAction->limitToProject($project->id)->execute($search);

        return view('', compact('project', 'searchResults', 'search'));
    }
}
