<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\ShowDatasetViewModel;

class EditDatasetWebController extends Controller
{
    public function __invoke(Project $project, $datasetId)
    {
        $dataset = Dataset::with('experiments')->where('id', $datasetId)->first();
        $experiments = $project->experiments()->get();
        $viewModel = new ShowDatasetViewModel($project, $dataset, $experiments);

        return view('app.projects.datasets.edit', $viewModel);
    }
}
