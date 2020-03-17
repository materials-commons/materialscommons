<?php

namespace App\Actions\Workflows;

use App\Actions\Datasets\UpdateDatasetWorkflowSelectionAction;

class CreateWorkflowAndAddToDatasetAction
{
    /**
     * @var \App\Actions\Workflows\CreateWorkflowAction
     */
    private $createWorkflowAction;

    /**
     * @var \App\Actions\Datasets\UpdateDatasetWorkflowSelectionAction
     */
    private $updateDatasetWorkflowSelectionAction;

    public function __construct()
    {
        $this->createWorkflowAction = new CreateWorkflowAction();
        $this->updateDatasetWorkflowSelectionAction = new UpdateDatasetWorkflowSelectionAction();
    }

    public function __invoke($data, $projectId, $userId, $dataset, $experiment = null)
    {
        $workflow = ($this->createWorkflowAction)($data, $projectId, $userId, $experiment);
        ($this->updateDatasetWorkflowSelectionAction)($workflow->id, $projectId, $dataset);
        return $workflow;
    }
}