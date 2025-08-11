<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Entity;
use App\Models\Project;
use App\Traits\Entities\PrevNextEntity;
use Illuminate\Http\Request;

class ShowEntitySpreadWebController extends Controller
{
    use PrevNextEntity;

    public function __invoke(Request $request, $projectId)
    {
        $project = Project::with('entities')->findOrFail($projectId);
        $entityId = $request->route('entity');
        $entity = Entity::with(['activities', 'tags'])->findOrFail($entityId);
        $activityIds = $entity->activities->pluck('id')->toArray();
        $activities = Activity::with(['attributes.values', 'entityStates.attributes.values', 'files', 'tags'])
                              ->whereIn('id', $activityIds)
                              ->orderBy('eindex')
                              ->get();

        // Compute previous and next entities by first creating the query to get the ordered.
        // The computePrevNext will set $this->prevEntity and $this->nextEntity.
        $category = $entity->category;
        $allEntities = Entity::has('experiments')
                             ->with(['activities', 'experiments'])
                             ->where('category', $category)
                             ->whereNull('dataset_id')
                             ->where('project_id', $project->id)
                             ->orderBy('name')
                             ->get();
        $this->computePrevNext($allEntities, $entityId);

        return view('app.projects.entities.show-spread', [
            'project'     => $project,
            'entity'      => $entity,
            'activities'  => $activities,
            'allEntities' => $allEntities,
            'nextEntity'  => $this->nextEntity,
            'prevEntity'  => $this->prevEntity,
        ]);
    }
}
