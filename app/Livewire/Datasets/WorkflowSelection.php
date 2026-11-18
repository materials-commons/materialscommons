<?php

namespace App\Livewire\Datasets;

use App\Actions\Datasets\UpdateDatasetWorkflowSelectionAction;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\Workflow;
use App\Services\AuthService;
use Illuminate\Support\Collection;
use Livewire\Component;

class WorkflowSelection extends Component
{
    public Project $project;
    public Dataset $dataset;
    public Collection $workflows;

    public int $workflowsCount = 0;

    public function mount(Project $project, Dataset $dataset, Collection $workflows): void
    {
        abort_unless(AuthService::userCanAccessProjectId(auth()->user(), $project->id), 404, 'No such project');
        abort_unless($dataset->project_id === $project->id, 404, 'No such dataset');

        $this->project = $project;
        $this->dataset = $dataset;
        $this->workflows = $workflows;
        $this->workflowsCount = $dataset->workflows->count();
    }

    public function workflowExperiments(Workflow $workflow): string
    {
        return $workflow->experiments()
                        ->where('project_id', $this->project->id)
                        ->pluck('name')
                        ->join(', ');
    }

    public function workflowInDataset(Workflow $workflow): bool
    {
        return $this->dataset
            ->fresh()
            ->workflows()
            ->where('workflows.id', $workflow->id)
            ->exists();
    }

    public function toggleWorkflow(int $workflowId): void
    {
        $workflow = Workflow::findOrFail($workflowId);

        abort_unless($workflow->project_id === $this->project->id, 404, 'No such workflow');

        $action = new UpdateDatasetWorkflowSelectionAction();

        $this->dataset = $action($workflow->id, $this->project->id, $this->dataset->fresh());
        $this->dataset->globus_acl_id = null;
        $this->workflowsCount = $this->dataset->workflows()->count();
    }

    public function render()
    {
        return view('livewire.datasets.workflow-selection');
    }
}
