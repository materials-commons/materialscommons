<?php

namespace App\Livewire\Entities;

use Livewire\Component;

class VerticalFlowView extends Component
{
    public $activities = [];
    public $usedActivities = [];
    public $entities;
    public $selectedEntityIds = [];
    public $showFlow = false;

    public function mount($activities, $usedActivities, $entities)
    {
        $this->activities = $activities;
        $this->usedActivities = $usedActivities;
        $this->entities = $entities;
    }

    public function toggleEntity($entityId)
    {
        if (in_array($entityId, $this->selectedEntityIds)) {
            $this->selectedEntityIds = array_values(array_diff($this->selectedEntityIds, [$entityId]));
        } else {
            $this->selectedEntityIds[] = $entityId;
        }
    }

    public function selectAll($entityIds)
    {
        $this->selectedEntityIds = $entityIds;
    }

    public function deselectAll()
    {
        $this->selectedEntityIds = [];
    }

    public function viewFlow()
    {
        $this->showFlow = true;
    }

    public function closeFlow()
    {
        $this->showFlow = false;
    }

    public function getSelectedEntitiesProperty()
    {
        return collect($this->selectedEntityIds);
    }

    public function getActivitiesForEntity($entityId)
    {
        if (!isset($this->usedActivities[$entityId])) {
            return [];
        }

        $activities = [];
        foreach ($this->usedActivities[$entityId] as $index => $isUsed) {
            if ($isUsed && isset($this->activities[$index])) {
                $activity = $this->activities[$index];
                // Handle both array and object formats
                $activities[] = is_array($activity) ? $activity['name'] : $activity->name;
            }
        }

        return $activities;
    }

    public function render()
    {
        return view('livewire.entities.vertical-flow-view');
    }
}
