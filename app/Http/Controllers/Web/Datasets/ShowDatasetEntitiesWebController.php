<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Project;

class ShowDatasetEntitiesWebController extends Controller
{
    public function __invoke(CreateUsedActivitiesForEntitiesAction $createUsedActivities, Project $project, $datasetId)
    {
        $dataset = Dataset::with(['experiments', 'tags'])->find($datasetId);
        $entities = $this->getEntitiesForDataset($dataset);
        $activities = Entity::activityNamesForEntities($entities);
        return view('app.projects.datasets.show', [
            'project'        => $project,
            'dataset'        => $dataset,
            'entities'       => $entities,
            'activities'     => $activities,
            'usedActivities' => $createUsedActivities->execute($activities, $entities),
        ]);
    }

    private function getEntitiesForDataset($dataset)
    {
        // unpublished dataset so get entities from template
        if (is_null($dataset->published_at)) {
            return $dataset->entitiesFromTemplate();
        }

        // published dataset so get the entities that were associated with
        // the dataset from iterating through the template
        $dataset->load('entities');
        return $dataset->entities;
    }
}