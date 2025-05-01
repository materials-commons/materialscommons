<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Datasets\Traits\DatasetEntities;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\ShowDatasetOverviewViewModel;

class ShowDatasetWorkflowsWebController extends Controller
{
    use DatasetEntities;

    public function __invoke(Project $project, $datasetId)
    {
        $dataset = Dataset::with(['tags', 'workflows'])->findOrFail($datasetId);
        $showDatasetOverviewViewModel = (new ShowDatasetOverviewViewModel())
            ->withProject($project)
            ->withDataset($dataset)
            ->withEditRoute(route('projects.datasets.workflows.edit', [$project, $dataset]))
            ->withWorkflows($dataset->workflows->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE))
            ->withEntities($this->getEntitiesForDataset($dataset));
        return view('app.projects.datasets.show', $showDatasetOverviewViewModel);
    }
}