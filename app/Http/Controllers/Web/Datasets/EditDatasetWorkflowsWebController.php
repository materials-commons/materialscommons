<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\Workflow;
use App\ViewModels\Datasets\EditDatasetWorkflowsViewModel;

class EditDatasetWorkflowsWebController extends Controller
{

    public function __invoke($projectId, $datasetId)
    {
        $project = Project::findOrFail($projectId);
        $dataset = Dataset::with('workflows.experiments')->findOrFail($datasetId);
        $user = auth()->user();
        $workflows = $this->getProjectWorkflows($projectId);
        $viewModel = new EditDatasetWorkflowsViewModel($project, $dataset, $user, $workflows);
        return view('app.projects.datasets.edit-workflows', $viewModel);
    }

    private function getProjectWorkflows($projectId)
    {
        return Workflow::with('experiments')->whereIn('id', function($q) use ($projectId) {
            $q->select('workflow_id')->from('item2workflow')
              ->where('item_type', 'App\Models\Experiment')
              ->whereIn('item_id', function($q2) use ($projectId) {
                  $q2->select('id')->from('experiments')->where('project_id', $projectId);
              });
        })->get();
    }
}
