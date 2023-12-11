<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Project;
use App\Traits\GroupByActivityType;
use Illuminate\Http\Request;

class ShowEntityWebController extends Controller
{
    use GroupByActivityType;

    public function __invoke(Request $request, $projectId)
    {
        $project = Project::with('entities')->findOrFail($projectId);
        $entityId = $request->route('entity');
        $entity = Entity::with(['activities.tags', 'tags'])->findOrFail($entityId);
        $activityIds = $entity->activities->pluck('id')->toArray();

        return view('app.projects.entities.show-grouped', [
            'project'                    => $project,
            'entity'                     => $entity,
            'activityTypes'              => $this->getActivityTypes($activityIds),
            'attributesByActivityType'   => $this->getAttributesByActivityType($activityIds),
            'filesByActivityType'        => $this->getFilesByActivityType($activityIds),
            'measurementsByActivityType' => $this->getMeasurementsByActivityType($activityIds),
        ]);
    }
}
