<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class ShowDatasetActivitiesWebController extends Controller
{
    public function __invoke(Project $project, $datasetId)
    {
        $dataset = Dataset::with(['tags', 'activities'])->find($datasetId);
        return view('app.projects.datasets.show', compact('project', 'dataset'));
    }
}
