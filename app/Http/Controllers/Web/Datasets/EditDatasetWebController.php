<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Datasets\Traits\HasExtendedInfo;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\EditOrCreateDataDatasetViewModel;

class EditDatasetWebController extends Controller
{
    use HasExtendedInfo;

    public function __invoke(Project $project, $datasetId, $folderId = null)
    {
        $dataset = Dataset::with(['communities', 'experiments', 'tags', 'papers'])->findOrFail($datasetId);
        $communities = Community::where('public', true)->get();
        $experiments = $project->experiments()->get();
        $viewModel = new EditOrCreateDataDatasetViewModel($project, $dataset, auth()->user());
        $viewModel->withCommunities($communities)
                  ->withDatasetEntities($this->getEntitiesForDataset($dataset))
                  ->withExperiments($experiments);

        return view('app.projects.datasets.edit', $viewModel);
    }
}
