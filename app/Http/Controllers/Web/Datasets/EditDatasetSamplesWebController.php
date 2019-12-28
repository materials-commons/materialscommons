<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\EditDatasetViewModel;

class EditDatasetSamplesWebController extends Controller
{
    public function __invoke($projectId, $datasetId)
    {
        $project = Project::with('entities.experiments')->findOrFail($projectId);
        $dataset = Dataset::with(['entities.experiments', 'communities', 'experiments'])->findOrFail($datasetId);
        $user = auth()->user();
        $communities = Community::where('public', true)->get();
        $experiments = $project->experiments()->get();
        $viewModel = new EditDatasetViewModel($project, $dataset, $user);
        $viewModel->withCommunities($communities)
                  ->withExperiments($experiments);
        return view('app.projects.datasets.edit', $viewModel);
    }
}
