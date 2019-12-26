<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\EditDatasetActivitiesViewModel;

class EditDatasetActivitiesWebController extends Controller
{
    public function __invoke($projectId, $datasetId)
    {
        $project = Project::with('activities')->findOrFail($projectId);
        $dataset = Dataset::with('activities.experiments')->findOrFail($datasetId);
        $user = auth()->user();
        $viewModel = new EditDatasetActivitiesViewModel($project, $dataset, $user);
        return view('app.projects.datasets.edit-processes', $viewModel);
    }
}
