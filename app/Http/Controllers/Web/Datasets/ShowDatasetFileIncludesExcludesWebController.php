<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Datasets\Traits\DatasetEntities;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\ShowDatasetOverviewViewModel;
use Illuminate\Http\Request;

class ShowDatasetFileIncludesExcludesWebController extends Controller
{
    use DatasetEntities;

    public function __invoke(Request $request, Project $project, $datasetId)
    {
        $dataset = Dataset::with('tags')->find($datasetId);
        $showDatasetOverviewViewModel = (new ShowDatasetOverviewViewModel())
            ->withProject($project)
            ->withDataset($dataset)
            ->withEntities($this->getEntitiesForDataset($dataset));
        return view('app.projects.datasets.show', $showDatasetOverviewViewModel);
    }
}
