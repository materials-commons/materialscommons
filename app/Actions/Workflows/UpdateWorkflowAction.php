<?php

namespace App\Actions\Workflows;

use App\Models\Workflow;

class UpdateWorkflowAction
{
    public function __invoke(Workflow $workflow, $data)
    {
        return tap($workflow)->update($data)->fresh();
    }
}