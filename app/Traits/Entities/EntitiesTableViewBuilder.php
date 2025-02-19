<?php

namespace App\Traits\Entities;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Models\Entity;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use function view;

trait EntitiesTableViewBuilder
{
    use EntityAndAttributeQueries;

    private function createProjectEntitiesView($view): View|Closure|string
    {
        $createUsedActivities = new CreateUsedActivitiesForEntitiesAction();
        $entities = Entity::has('experiments')
                          ->with(['activities', 'experiments'])
                          ->where('category', $this->category)
                          ->where('project_id', $this->project->id)
                          ->get();

        $activities = $this->getProjectActivityNamesForEntities($this->project->id, $entities);
        $usedActivities = $createUsedActivities->execute($activities, $entities);

        $query = Entity::has('experiments')
                       ->with(['activities', 'experiments'])
                       ->where('category', $this->category)
                       ->where('project_id', $this->project->id);

        $query = $this->applySearch($query);
        $query = $this->applySort($query);

        $paged = $query->paginate(100);

        return view($view, [
            'entities'       => $paged,
            'activities'     => $activities,
            'usedActivities' => $usedActivities,
            'showExperiment' => true
        ]);
    }

    private function createExperimentEntitiesView($view): View|Closure|string
    {
        $createUsedActivities = new CreateUsedActivitiesForEntitiesAction();
        $orderByColumn = "name";
        $nullCount = DB::table('experiment2activity')
                       ->where('experiment_id', $this->experiment->id)
                       ->join(
                           'activities',
                           'experiment2activity.activity_id',
                           '=',
                           'activities.id'
                       )
                       ->where('activities.name', '<>', 'Create Samples')
                       ->select('activities.name', 'activities.eindex')
                       ->whereNull('eindex')
                       ->distinct()
                       ->get()
                       ->count();

        if ($nullCount == 0) {
            $orderByColumn = "eindex";
        }

        if ($this->category == 'experimental') {
            $entities = $this->experiment->experimental_entities()->with('activities')->get();
            $query = $this->experiment->experimental_entities()->with('activities');
        } else {
            $entities = $this->experiment->computational_entities()->with('activities')->get();
            $query = $this->experiment->computational_entities()->with('activities');
        }

        $query = $this->applySearch($query);
        $query = $this->applySort($query);
        $paged = $query->paginate(100);

        $activities = $this->getExperimentActivityNamesEindexForEntities($this->experiment->id, $entities,
            $orderByColumn);
        $usedActivities = $createUsedActivities->execute($activities, $entities);

        return view($view, [
            'entities'       => $paged,
            'activities'     => $activities,
            'usedActivities' => $usedActivities,
            'showExperiment' => false,
        ]);
    }
}