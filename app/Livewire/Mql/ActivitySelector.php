<?php

namespace App\Livewire\Mql;

use App\Models\EntityView;
use App\Models\Project;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ActivitySelector extends Component
{
    public Project $project;
    public string $category;

    public array $activityNames = [];

    public string $selectedActivity = '';

    public function mount(Project $project, string $category, $activities): void
    {
        abort_unless(AuthService::userCanAccessProjectId(auth()->user(), $project->id), 404, 'No such project');

        $this->project = $project;
        $this->category = $category;

        $this->activityNames = collect($activities)
            ->pluck('name')
            ->values()
            ->all();
    }

    public function updatedSelectedActivity(): void
    {
        if ($this->selectedActivity === '') {
            $this->dispatch('mql-activity-entities-matched', entities: []);
            return;
        }

        $innerQuery = DB::table('entity_attrs_by_proj_exp')
                        ->distinct()
                        ->select('entity_name')
                        ->where('project_id', $this->project->id)
                        ->whereIn('activity_name', [$this->selectedActivity]);

        $matches = EntityView::query()
                             ->select(['entity_name', 'activity_name'])
                             ->where('project_id', $this->project->id)
                             ->whereIn('entity_name', $innerQuery)
                             ->get()
                             ->groupBy('entity_name')
                             ->filter(function ($item) {
                                 return $item
                                     ->keyBy(function ($i) {
                                         return $i->activity_name;
                                     })
                                     ->has([$this->selectedActivity]);
                             });

        $this->dispatch('mql-activity-entities-matched', entities: $matches->keys()->values()->all());
    }

    public function render()
    {
        return view('livewire.mql.activity-selector', [
            'activityNames' => $this->activityNames,
        ]);
    }
}
