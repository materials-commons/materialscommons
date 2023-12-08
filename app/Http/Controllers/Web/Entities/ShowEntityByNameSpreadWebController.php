<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Http\Request;
use function view;

class ShowEntityByNameSpreadWebController extends Controller
{
    public function __invoke(Request $request, Project $project, Experiment $experiment)
    {
        $name = $request->input("name");
        $project = $project->load('entities');
        $entity = Entity::with(['activities', 'tags'])
                        ->where('name', urldecode($name))
                        ->where('project_id', $project->id)
                        ->whereHas('experiments', function ($q) use ($experiment) {
                            $q->where("experiment2entity.experiment_id", $experiment->id);
                        })
                        ->first();

        if (is_null($entity)) {
            flash("No such sample in experiment {$name}")->error();
            return redirect(route('projects.experiments.entities', [$project, $experiment]));
        }

        $activityIds = $entity->activities->pluck('id')->toArray();
        $activities = Activity::with(['attributes.values', 'entityStates.attributes.values', 'files', 'tags'])
                              ->whereIn('id', $activityIds)
                              ->orderBy('eindex')
                              ->get();
        return view('app.projects.entities.show-spread', [
            'experiment' => $experiment,
            'project'    => $project,
            'entity'     => $entity,
            'activities' => $activities,
        ]);
    }
}
