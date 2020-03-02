<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\EditDatasetViewModel;

class EditDatasetWorkflowsWebController extends Controller
{

    public function __invoke($projectId, $datasetId)
    {
        $project = Project::with(['experiments', 'workflows'])->findOrFail($projectId);
        $dataset = Dataset::with(['workflows.experiments', 'communities', 'experiments'])->findOrFail($datasetId);
        $workflows = $project->workflows;
        $communities = Community::where('public', true)->get();
        $experiments = $project->experiments;
        $viewModel = new EditDatasetViewModel($project, $dataset, auth()->user());
        $viewModel->withCommunities($communities)
                  ->withExperiments($experiments)
                  ->withWorkflows($workflows);
        return view('app.projects.datasets.edit', $viewModel);
    }

//    private function getProjectWorkflows($projectId)
//    {
//        return Workflow::with('experiments')->whereIn('id', function ($q) use ($projectId) {
//            $q->select('workflow_id')->from('item2workflow')
//              ->where('item_type', 'App\Models\Experiment')
//              ->whereIn('item_id', function ($q2) use ($projectId) {
//                  $q2->select('id')->from('experiments')->where('project_id', $projectId);
//              });
//        })->get();
//    }
}
