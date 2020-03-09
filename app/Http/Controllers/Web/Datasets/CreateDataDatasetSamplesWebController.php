<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\EditOrCreateDataDatasetViewModel;

class CreateDataDatasetSamplesWebController extends Controller
{
    public function __invoke(Project $project, $datasetId)
    {
        $dataset = Dataset::with(['entities.experiments'])->findOrFail($datasetId);
        $user = auth()->user();
        $viewModel = new EditOrCreateDataDatasetViewModel($project, $dataset, $user);
        return view('app.projects.datasets.create-data', $viewModel);
    }
}
