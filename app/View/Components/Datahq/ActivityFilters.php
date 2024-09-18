<?php

namespace App\View\Components\Datahq;

use App\Models\Entity;
use App\Models\Project;
use App\Traits\Entities\EntityAndAttributeQueries;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActivityFilters extends Component
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
        $entities = Entity::has('experiments')
                          ->with(['activities', 'experiments'])
                          ->where('category', 'experimental')
                          ->where('project_id', $this->project->id)
                          ->get();
        $activities = $this->getProjectActivityNamesForEntities($this->project->id, $entities);

        return view('components.datahq.activity-filters', [
            'activities' => $activities,
        ]);
    }
}
