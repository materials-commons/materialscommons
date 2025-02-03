<?php

namespace App\Livewire\Projects\Entities;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\Entities\EntityAndAttributeQueries;
use App\Traits\Table\BaseLivewireTable;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use function is_null;
use function view;

class EntitiesTable extends Component
{
    use EntityAndAttributeQueries;
    use WithPagination;
    use BaseLivewireTable;

    public Project $project;
    public ?Experiment $experiment = null;
    public $category = 'experimental';
    public bool $showCheckbox = false;

    public function render()
    {
        if (is_null($this->experiment)) {
            return $this->createProjectEntitiesView();
        } else {
            return $this->createExperimentEntitiesView();
        }
    }

    private function applySearch($query)
    {
        if ($this->search != '') {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        return $query;
    }

    private function applySort($query)
    {
        if (blank($this->sortCol)) {
            return $query;
        }

        return $query->orderBy($this->sortCol, $this->sortAsc ? 'asc' : 'desc');
    }

    private function createProjectEntitiesView(): View|Closure|string
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

        return view('livewire.projects.entities.entities-table', [
            'entities'       => $paged,
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

        return view('livewire.projects.entities.entities-table', [
            'entities'       => $paged,
            'activities'     => $activities,
            'usedActivities' => $usedActivities,
            'showExperiment' => false,
        ]);
    }
}
