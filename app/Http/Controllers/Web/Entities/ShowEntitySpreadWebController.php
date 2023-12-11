<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Entity;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowEntitySpreadWebController extends Controller
{
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
        return view('app.projects.entities.show-spread', [
            'project'    => $project,
            'entity'     => $entity,
            'activities' => $activities,
        ]);
    }
}
