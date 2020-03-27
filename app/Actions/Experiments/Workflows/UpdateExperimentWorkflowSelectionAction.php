<?php

namespace App\Actions\Experiments\Workflows;

use App\Models\Experiment;
use App\Models\Workflow;

class UpdateExperimentWorkflowSelectionAction
{
    public function execute($workflowId, $projectId, Experiment $experiment)
    {
        abort_unless($this->workflowInProject($workflowId, $projectId), 400, "No such workflow");
        $experiment->workflows()->toggle($workflowId);
        return $experiment;
    }

    private function workflowInProject($workflowId, $projectId)
    {
        $workflows = Workflow::where('project_id', $projectId)->get();
        $workflow = $workflows->find($workflowId);
        return $workflow !== null;
    }
}