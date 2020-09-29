<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\EditOrCreateDataDatasetViewModel;

class EditDatasetWebController extends Controller
{
    public function __invoke(Project $project, $datasetId, $folderId = null)
    {
        $dataset = Dataset::with(['communities', 'experiments', 'tags', 'papers'])->findOrFail($datasetId);
        $communities = Community::where('public', true)->get();
        $experiments = $project->experiments()->get();
        $viewModel = new EditOrCreateDataDatasetViewModel($project, $dataset, auth()->user());
        $viewModel->withCommunities($communities)
                  ->withExperiments($experiments);

        return view('app.projects.datasets.edit', $viewModel);
    }
}
