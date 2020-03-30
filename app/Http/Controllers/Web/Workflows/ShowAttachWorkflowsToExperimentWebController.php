<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\ViewModels\Experiments\ExperimentWorkflowsViewModel;

class ShowAttachWorkflowsToExperimentWebController extends Controller
{
    public function __invoke($projectId, $experimentId)
    {
        $project = Project::with('workflows')->findOrFail($projectId);
        $experiment = Experiment::with('workflows')->findOrFail($experimentId);
        $viewModel = (new ExperimentWorkflowsViewModel())
            ->withProject($project)
            ->withExperiment($experiment)
            ->withUser(auth()->user());
        return view('app.projects.experiments.workflows.attach', $viewModel);
    }
}
