<?php

namespace App\Http\Controllers\Web\Activities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowActivityWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $experimentId = $request->route('experiment');
        $experiment   = null;

        $activityId = $request->route('activity');
        $activity   = Activity::with('entities')->where('id', $activityId)->first();

        if ($experimentId !== null) {
            $experiment = Experiment::find($experimentId);
        }

        return view('app.activities.show', compact('project', 'activity', 'experiment'));
    }
}
