<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Entity;
use App\Models\Project;

class CompareEntitiesWebController extends Controller
{
    public function __invoke(Project $project, $entityId1, $entityId2)
    {
        $entity1 = Entity::with(['activities'])->findOrFail($entityId1);
        $activityIds = $entity1->activities->pluck('id')->toArray();
        $entity1Activities = Activity::with(['attributes.values', 'entityStates.attributes.values', 'files'])
                                     ->whereIn('id', $activityIds)
                                     ->get();

        $entity2 = Entity::with(['activities'])->findOrFail($entityId2);
        $activityIds = $entity2->activities->pluck('id')->toArray();
        $entity2Activities = Activity::with(['attributes.values', 'entityStates.attributes.values', 'files'])
                                     ->whereIn('id', $activityIds)
                                     ->get();

        return view('app.projects.entities.compare', [
            'project'           => $project,
            'entity1'           => $entity1,
            'entity1Activities' => $entity1Activities,
            'entity2'           => $entity2,
            'entity2Activities' => $entity2Activities,
        ]);
    }
}
