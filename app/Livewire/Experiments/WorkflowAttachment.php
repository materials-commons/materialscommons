<?php

namespace App\Livewire\Experiments;

use App\Actions\Experiments\Workflows\UpdateExperimentWorkflowSelectionAction;
use App\Models\Experiment;
use App\Models\Project;
use App\Models\Workflow;
use App\Services\AuthService;
use Livewire\Component;

class WorkflowAttachment extends Component
{
    public Project $project;
    public Experiment $experiment;

    public function mount(Project $project, Experiment $experiment): void
    {
        abort_unless(AuthService::userCanAccessProjectId(auth()->user(), $project->id), 404, 'No such project');
        abort_unless($experiment->project_id === $project->id, 404, 'No such experiment');

        $this->project = $project;
        $this->experiment = $experiment;
    }

    public function toggleWorkflow(int $workflowId): void
    {
        $workflow = Workflow::findOrFail($workflowId);

        abort_unless($workflow->project_id === $this->project->id, 404, 'No such workflow');

        $action = new UpdateExperimentWorkflowSelectionAction();

        $this->experiment = $action->execute($workflow->id, $this->project->id, $this->experiment->fresh());
    }

    public function workflowInExperiment(Workflow $workflow): bool
    {
        return $this->experiment
            ->fresh()
            ->workflows()
            ->where('workflows.id', $workflow->id)
            ->exists();
    }

    public function render()
    {
        return view('livewire.experiments.workflow-attachment', [
            'workflows' => $this->project->workflows,
        ]);
    }
}
