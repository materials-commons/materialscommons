<?php

namespace App\Livewire\Entities;

use App\Models\Activity;
use App\Models\Entity;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class EntitySidebar extends Component
{
    public bool $show = false;

    public ?int $entityId = null;

    public $entity;

    public string $entityLabel = 'Sample';
    public string $category = 'experimental';

    public int $filesCount = 0;
    public $uniqueActivities;

    public $experimentId = 0;

    #[On('showEntitySidebar')]
    public function showEntitySidebar(int $entityId, int $experimentId, string $category = 'experimental'): void
    {
        if ($this->show && $this->entityId === $entityId) {
            $this->close();

            return;
        }

        $this->experimentId = $experimentId;
        $this->category = $category;

        $this->entityId = $entityId;
        $this->entityLabel = $category === 'computational' ? 'Computation' : 'Sample';

        $this->entity = Entity::query()
                              ->with([
                                  'activities.files', 'activities.attributes', 'activities.entityStates.attributes', 'experiments'
                              ])
                              ->withCount(['files', 'entityStates', 'activities'])
                              ->findOrFail($entityId);
        $this->filesCount = 0;
        foreach ($this->entity->activities as $activity) {
            $this->filesCount += $activity->files->count();
        }

        $this->uniqueActivities = $this->entity->activities
            ->groupBy('name')
            ->map(function ($activities, $name) {
                return [
                    'name'               => $name,
                    'count'              => $activities->count(),
                    'files_count'        => $activities->sum(fn($activity) => $activity->files->count()),
                    'attributes_count'   => $activities->sum(fn($activity) => $activity->attributes->count()),
                    'measurements_count' => $activities->sum(fn($activity) => $activity->entityStates->first()?->attributes?->count()),
                ];
            });

        ray($this->uniqueActivities);

        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
        $this->entityId = null;
        $this->filesCount = 0;
    }

    public function render()
    {
        return view('livewire.entities.entity-sidebar');
    }
}
