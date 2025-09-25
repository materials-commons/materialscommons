<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\Entities\PrevNextEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function view;

class ShowEntityByNameSpreadWebController extends Controller
{
    use PrevNextEntity;

    public function __invoke(Request $request, Project $project, Experiment $experiment)
    {
        $name = $request->input("name");
        $fromExperiment = $this->isFromExperiment($request);
        $project = $project->load('entities');
        $experiment->load('sheet');
        $entity = Entity::with(['activities', 'tags'])
                        ->where('name', urldecode($name))
                        ->where('project_id', $project->id)
                        ->whereNull('dataset_id')
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

        // Compute previous and next entities by first creating the query to get the ordered.
        // The computePrevNext will set $this->prevEntity and $this->nextEntity.
        $category = $entity->category;
        if ($fromExperiment) {
            if ($category == 'experimental') {
                $allEntities = $experiment->experimental_entities()
                                          ->with(['activities', 'experiments'])
                                          ->orderBy('name')
                                          ->get();
            } else {
                // computational
                $allEntities = $experiment->computational_entities()
                                          ->with(['activities', 'experiments'])
                                          ->orderBy('name')
                                          ->get();
            }
        } else {
            $allEntities = Entity::has('experiments')
                                 ->with(['activities', 'experiments'])
                                 ->where('category', $category)
                                 ->whereNull('dataset_id')
                                 ->where('project_id', $project->id)
                                 ->orderBy('name')
                                 ->get();
        }
        $this->computePrevNext($allEntities, $entity->id);;

        return view('app.projects.entities.show-spread', [
            'user'        => auth()->user(),
            'experiment'  => $experiment,
            'project'     => $project,
            'entity'      => $entity,
            'activities'  => $activities,
            'allEntities' => $allEntities,
            'nextEntity'  => $this->nextEntity,
            'prevEntity'  => $this->prevEntity,
        ]);
    }
}
