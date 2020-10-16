<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Datasets\Traits\HasExtendedInfo;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\ShowDatasetOverviewViewModel;
use Illuminate\Http\Request;

class ShowDatasetExperimentsWebController extends Controller
{
    use HasExtendedInfo;

    public function __invoke(Request $request, Project $project, $datasetId)
    {
        $dataset = Dataset::with('experiments', 'tags')->find($datasetId);
        $showDatasetOverviewViewModel = (new ShowDatasetOverviewViewModel())
            ->withProject($project)
            ->withDataset($dataset)
            ->withEntities($this->getEntitiesForDataset($dataset));
        return view('app.projects.datasets.show', $showDatasetOverviewViewModel);
    }
}
