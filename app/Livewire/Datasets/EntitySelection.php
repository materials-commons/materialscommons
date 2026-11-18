<?php

namespace App\Livewire\Datasets;

use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Project;
use App\Services\AuthService;
use Livewire\Component;

class EntitySelection extends Component
{
    public Project $project;
    public Dataset $dataset;

    public int $samplesCount = 0;

    public function mount(Project $project, Dataset $dataset, int $entitiesCountInDataset): void
    {
        abort_unless(AuthService::userCanAccessProjectId(auth()->user(), $project->id), 404, 'No such project');
        abort_unless($dataset->project_id === $project->id, 404, 'No such dataset');

        $this->project = $project;
        $this->dataset = $dataset;
        $this->samplesCount = $entitiesCountInDataset;
    }

    public function toggleEntity(int $entityId): void
    {
        $entity = Entity::findOrFail($entityId);

        abort_unless($entity->project_id === $this->project->id, 404, 'No such entity');

        $action = new UpdateDatasetEntitySelectionAction();

        $this->dataset = $action($entity, $this->dataset->fresh());
        $this->dataset->globus_acl_id = null;
        $this->samplesCount = $this->dataset->entities()->count();
    }

    public function selectAllEntities(): void
    {
        $action = new UpdateDatasetEntitySelectionAction();

        foreach ($this->project->entities as $entity) {
            if (!$this->dataset->fresh()->entities()->where('entities.id', $entity->id)->exists()) {
                $this->dataset = $action($entity, $this->dataset->fresh());
            }
        }

        $this->samplesCount = $this->dataset->fresh()->entities()->count();
    }

    public function unselectAllEntities(): void
    {
        $action = new UpdateDatasetEntitySelectionAction();

        foreach ($this->project->entities as $entity) {
            if ($this->dataset->fresh()->entities()->where('entities.id', $entity->id)->exists()) {
                $this->dataset = $action($entity, $this->dataset->fresh());
            }
        }

        $this->samplesCount = 0;
    }

    public function render()
    {
        return view('livewire.datasets.entity-selection', [
            'entities' => $this->project->entities,
        ]);
    }
}
