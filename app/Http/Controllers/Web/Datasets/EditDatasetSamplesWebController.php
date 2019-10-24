<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\EditDatasetEntitiesViewModel;

class EditDatasetSamplesWebController extends Controller
{
    public function __invoke($projectId, $datasetId)
    {
        $project = Project::with('entities')->findOrFail($projectId);
        $dataset = Dataset::with('entities.experiments')->findOrFail($datasetId);
        $user = auth()->user();
        $viewModel = new EditDatasetEntitiesViewModel($project, $dataset, $user);
        return view('app.projects.datasets.edit-samples', $viewModel);
    }
}
