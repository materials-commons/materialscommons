<?php

namespace App\Actions\Datasets;

use App\Models\Workflow;

class UpdateDatasetWorkflowSelectionAction
{
    public function __invoke($workflowId, $projectId, $dataset)
    {
        abort_unless($this->workflowInProject($workflowId, $projectId), 400, "No such workflow");
        $dataset->workflows()->toggle($workflowId);
        return $dataset;
    }

    private function workflowInProject($workflowId, $projectId)
    {
//        $workflows = Workflow::whereIn('id', function($q) use ($projectId) {
//            $q->select('workflow_id')->from('item2workflow')
//              ->where('item_type', 'App\Models\Experiment')
//              ->whereIn('item_id', function($q2) use ($projectId) {
//                  $q2->select('id')->from('experiments')->where('project_id', $projectId);
//              });
//        })->get();

        $workflows = Workflow::where('project_id', $projectId)->get();

        $workflow = $workflows->find($workflowId);
        return $workflow !== null;
    }
}