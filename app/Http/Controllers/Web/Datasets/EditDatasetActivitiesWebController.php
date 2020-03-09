<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\EditOrCreateDataDatasetViewModel;

class EditDatasetActivitiesWebController extends Controller
{
    public function __invoke($projectId, $datasetId)
    {
        $project = Project::with(['activities.experiments', 'experiments'])->findOrFail($projectId);
        $dataset = Dataset::with(['activities.experiments', 'communities', 'experiments'])->findOrFail($datasetId);
        $user = auth()->user();
        $viewModel = new EditOrCreateDataDatasetViewModel($project, $dataset, $user);
        $communities = Community::where('public', true)->get();
        $experiments = $project->experiments;
        $viewModel->withCommunities($communities)
                  ->withExperiments($experiments);

        return view('app.projects.datasets.edit', $viewModel);
    }
}
