<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;
use App\ViewModels\Entities\ShowEntityViewModel;
use Illuminate\Http\Request;

class ShowEntityWebController extends Controller
{
    public function __invoke(Request $request, $projectId)
    {
        $project = Project::with('entities')->findOrFail($projectId);
        $entityId = $request->route('entity');
        $entity = Entity::with(['activities'])->findOrFail($entityId);
        $activityIds = $entity->activities->pluck('id')->toArray();
        $activities = Activity::with(['attributes.values', 'entityStates.attributes.values', 'files'])
                              ->whereIn('id', $activityIds)
                              ->orderBy('name')
                              ->get();
        return view('app.projects.entities.show2', [
            'project'    => $project,
            'entity'     => $entity,
            'activities' => $activities,
        ]);
    }

    private function originalInvoke(Request $request, Project $project)
    {
        $entityId = $request->route('entity');
        $entity = Entity::with('activities')->where('id', $entityId)->first();
        $showEntityViewModel = new ShowEntityViewModel($project, $entity);

        $experimentId = $request->route('experiment');
        if ($experimentId !== null) {
            $experiment = Experiment::find($experimentId);
            $showEntityViewModel->setExperiment($experiment);
            return view('app.projects.experiments.samples.show', $showEntityViewModel);
        }

        return view('app.projects.entities.show', $showEntityViewModel);
    }
}
