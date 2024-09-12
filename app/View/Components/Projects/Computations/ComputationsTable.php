<?php

namespace App\View\Components\Projects\Computations;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Models\Entity;
use App\Models\Project;
use App\Traits\Entities\EntityAndAttributeQueries;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use function view;

class ComputationsTable extends Component
{
    use EntityAndAttributeQueries;

    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $createUsedActivities = new CreateUsedActivitiesForEntitiesAction();
        $entities = Entity::has('experiments')
                          ->with(['activities', 'experiments'])
                          ->where('category', 'computational')
                          ->where('project_id', $this->project->id)
                          ->get();

        $activities = $this->getProjectActivityNamesForEntities($this->project->id, $entities);
        $usedActivities = $createUsedActivities->execute($activities, $entities);

        return view('components.projects.samples.samples-table', [
            'project'        => $this->project,
            'entities'       => $entities,
            'activities'     => $activities,
            'usedActivities' => $usedActivities,
        ]);
    }
}
