<?php

namespace App\Http\Controllers\Web\Activities\Computations;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowComputationWebController extends Controller
{
    public function __invoke(Request $request, Project $project, Activity $activity)
    {
        $activity->load(['attributes.values', 'entityStates.attributes.values', 'files', 'tags']);
        return view('app.projects.activities.computations.show', [
            'project'  => $project,
            'activity' => $activity,
        ]);
    }
}
