<?php

namespace App\Actions\Datasets;

use Illuminate\Support\Facades\DB;

class UpdateDatasetWorkflowSelectionAction
{
    public function __invoke($workflowId, $dataset)
    {
        $dataset->workflows()->toggle($workflowId);
        return $dataset;
    }

    private function blah()
    {
        DB::table('workflows')->select('*')->whereIn('id', function($q) {
            $q->select('workflow_id')->from('item2workflow')
              ->where('item_type', 'App\Models\Experiment')
              ->whereIn('item_id', function($q2) {
                  $q2->select('id')->from('experiments')->where('project_id', 1);
              });
        })->get();
    }
}