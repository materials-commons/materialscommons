<?php

namespace App\Livewire\Datasets;

use App\Actions\Datasets\UpdateDatasetActivitySelectionAction;
use App\Models\Activity;
use App\Models\Dataset;
use App\Models\Project;
use App\Services\AuthService;
use Livewire\Component;

class ActivitySelection extends Component
{
    public Project $project;
    public Dataset $dataset;

    public function mount(Project $project, Dataset $dataset): void
    {
        abort_unless(AuthService::userCanAccessProjectId(auth()->user(), $project->id), 404, 'No such project');
        abort_unless($dataset->project_id === $project->id, 404, 'No such dataset');

        $this->project = $project;
        $this->dataset = $dataset;
    }

    public function toggleActivity(int $activityId): void
    {
        $activity = Activity::findOrFail($activityId);

        abort_unless($activity->project_id === $this->project->id, 404, 'No such activity');

        $action = new UpdateDatasetActivitySelectionAction();

        $this->dataset = $action($activity->id, $this->dataset->fresh());
        $this->dataset->globus_acl_id = null;
    }

    public function activityExperiments(Activity $activity): string
    {
        return $activity->experiments()
                        ->where('project_id', $this->project->id)
                        ->pluck('name')
                        ->join(', ');
    }

    public function activityInDataset(Activity $activity): bool
    {
        return $this->dataset
            ->fresh()
            ->activities()
            ->where('activities.id', $activity->id)
            ->exists();
    }

    public function render()
    {
        return view('livewire.datasets.activity-selection', [
            'activities' => $this->project->activities,
        ]);
    }
}
