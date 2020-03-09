<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\EditOrCreateDataDatasetViewModel;

class CreateDataDatasetWorkflowsWebController extends Controller
{

    public function __invoke($projectId, $datasetId)
    {
        $project = Project::with(['workflows'])->findOrFail($projectId);
        $dataset = Dataset::with(['workflows.experiments'])->findOrFail($datasetId);
        $workflows = $project->workflows;
        $viewModel = new EditOrCreateDataDatasetViewModel($project, $dataset, auth()->user());
        $viewModel->withWorkflows($workflows);
        return view('app.projects.datasets.create-data', $viewModel);
    }
}
