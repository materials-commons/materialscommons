<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Datasets\Traits\DatasetEntities;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Project;
use App\ViewModels\Datasets\ShowDatasetOverviewViewModel;

class ShowDatasetEntitiesWebController extends Controller
{
    use DatasetEntities;

    public function __invoke(CreateUsedActivitiesForEntitiesAction $createUsedActivities, Project $project, $datasetId)
    {
        $dataset = Dataset::with(['experiments', 'tags'])->find($datasetId);
        $entities = $this->getEntitiesForDataset($dataset);
        $activities = Entity::activityNamesForEntities($entities);
        $showDatasetOverviewViewModel = (new ShowDatasetOverviewViewModel())
            ->withProject($project)
            ->withDataset($dataset)
            ->withEditRoute(route('projects.datasets.samples.edit', [$project, $dataset]))
            ->withActivities(Entity::activityNamesForEntities($entities))
            ->withUsedActivities($createUsedActivities->execute($activities, $entities))
            ->withEntities($this->getEntitiesForDataset($dataset));
        return view('app.projects.datasets.show', $showDatasetOverviewViewModel);
    }
}