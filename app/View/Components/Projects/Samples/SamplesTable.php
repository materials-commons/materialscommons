<?php

namespace App\View\Components\Projects\Samples;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\Entities\EntityAndAttributeQueries;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use function view;

class SamplesTable extends Component
{
    use EntityAndAttributeQueries;

    public Project $project;
    public ?Experiment $experiment;

    public function __construct(Project $project, $experiment = null)
    {
        $this->project = $project;
        $this->experiment = $experiment;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (is_null($this->experiment)) {
            return $this->createProjectEntitiesView();
        } else {
            return $this->createExperimentEntitiesView();
        }
    }

    private function createProjectEntitiesView(): View|Closure|string
    {
        $createUsedActivities = new CreateUsedActivitiesForEntitiesAction();
        $entities = Entity::has('experiments')
                          ->with(['activities', 'experiments'])
                          ->where('category', 'experimental')
                          ->where('project_id', $this->project->id)
                          ->get();

        $activities = $this->getProjectActivityNamesForEntities($this->project->id, $entities);
        $usedActivities = $createUsedActivities->execute($activities, $entities);

        return view('components.projects.samples.samples-table', [
            'project'        => $this->project,
            'entities'       => $entities,
            'activities'     => $activities,
            'usedActivities' => $usedActivities,
            'showExperiment' => true
        ]);
    }

    private function createExperimentEntitiesView(): View|Closure|string
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

        $entities = $this->experiment->experimental_entities()->with('activities')->get();
        $activities = $this->getExperimentActivityNamesEindexForEntities($this->experiment->id, $entities,
            $orderByColumn);
        $usedActivities = $createUsedActivities->execute($activities, $entities);

        return view('components.projects.samples.samples-table', [
            'project'        => $this->project,
            'experiment'     => $this->experiment,
            'entities'       => $entities,
            'activities'     => $activities,
            'usedActivities' => $usedActivities,
            'showExperiment' => false,
        ]);
    }
}
