<?php

namespace App\Http\Controllers\Web\Activities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Experiment;
use App\Models\Project;
use App\ViewModels\Activities\ShowActivityViewModel;
use Illuminate\Http\Request;

class ShowActivityWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $activityId = $request->route('activity');
        $activity = Activity::with('attributes.values')->where('id', $activityId)->first();

        $showActivityViewModel = new ShowActivityViewModel($project, $activity);

        $experimentId = $request->route('experiment');
        if ($experimentId !== null) {
            $experiment = Experiment::find($experimentId);
            $showActivityViewModel->setExperiment($experiment);
            return view('app.projects.experiments.processes.show', $showActivityViewModel);
        }

        return view('app.projects.activities.show', $showActivityViewModel);
    }
}
