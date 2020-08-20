<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Experiments\DeleteExperimentAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\Experiments\SharedDatasets;

class DestroyExperimentWebController extends Controller
{
    use SharedDatasets;

    public function __invoke(DeleteExperimentAction $deleteExperimentAction, Project $project, Experiment $experiment)
    {
        $this->authorize('canDeleteExperiment', [$project, $experiment]);
        abort_if($this->hasAffectedPublishedDatasets($experiment), 403,
            "Samples from experiment used in published datasets");
        $deleteExperimentAction($experiment);
        return redirect(route('projects.show', [$project]));
    }

    private function canDelete(Project $project, Experiment $experiment)
    {
        $userId = auth()->id();
        if ($experiment->owner_id == $userId) {
            return true;
        }

        if ($project->owner_id == $userId) {
            return true;
        }

        return false;
    }
}
