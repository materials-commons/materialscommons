<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Experiments\ReloadExperimentAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;

class ReloadExperimentWebController extends Controller
{
    public function __invoke(Request $request, ReloadExperimentAction $reloadExperimentAction, Project $project,
        Experiment $experiment)
    {
        $request->validate(['file_id' => 'required|integer']);
        $fileId = $request->get('file_id');
        if ($reloadExperimentAction->execute($project, $experiment, $fileId, auth()->id())) {
            flash("Reloading experiment {$experiment->name} in background.")->success();
        } else {
            flash("Failed reloading, no changes made to experiment.")->error();
        }
        return redirect(route('projects.experiments.show', [$project, $experiment]));
    }
}
