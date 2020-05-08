<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Experiments\DeleteExperimentAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;

class DestroyExperimentWebController extends Controller
{
    public function __invoke(DeleteExperimentAction $deleteExperimentAction, Project $project, Experiment $experiment)
    {
        abort_unless($this->canDelete($project, $experiment), 403, "Not experiment owner");
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
