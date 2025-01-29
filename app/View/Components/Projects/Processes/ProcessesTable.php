<?php

namespace App\View\Components\Projects\Processes;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ProcessesTable extends Component
{
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
        return view('components.projects.processes.processes-table', [
            'project'          => $this->project,
            'activityEntities' => $this->getActivityEntityGroup($this->project->id),
        ]);
    }

    private function getActivityEntityGroup(int $projectId): Collection
    {
        return DB::table('activity2entity')
                 ->leftJoin('activities', function ($join) use ($projectId) {
                     $join->on('activities.id', '=', 'activity2entity.activity_id')
                          ->where('activities.project_id', $projectId);
                 })
                 ->join('entities', 'entities.id', '=', 'activity2entity.entity_id')
                 ->select('activities.name as activityName', 'entities.name', 'entities.id')
                 ->distinct()
                 ->get()
                 ->groupBy('activityName');
    }
}
