<?php

namespace App\Actions\Workflows;

use App\Models\Workflow;

class UpdateWorkflowAction
{
    public function __invoke(Workflow $workflow, $workflowText)
    {
        return tap($workflow)->update(['workflow' => $workflowText])->fresh();
    }
}