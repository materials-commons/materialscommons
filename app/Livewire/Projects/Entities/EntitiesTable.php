<?php

namespace App\Livewire\Projects\Entities;

use App\Models\Experiment;
use App\Models\Project;
use App\Traits\Entities\EntitiesTableViewBuilder;
use App\Traits\Table\BaseLivewireTable;
use Livewire\Component;
use Livewire\WithPagination;
use function is_null;

class EntitiesTable extends Component
{
    use EntitiesTableViewBuilder;
    use WithPagination;
    use BaseLivewireTable;

    public Project $project;
    public ?Experiment $experiment = null;
    public $category = 'experimental';
    public bool $showCheckbox = false;

    public function render()
    {
        if (is_null($this->experiment)) {
            return $this->createProjectEntitiesView('livewire.projects.entities.entities-table');
        } else {
            return $this->createExperimentEntitiesView('livewire.projects.entities.entities-table');
        }
    }

    private function applySearch($query)
    {
        if ($this->search != '') {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        return $query;
    }

    private function applySort($query)
    {
        if (blank($this->sortCol)) {
            return $query;
        }

        return $query->orderBy($this->sortCol, $this->sortAsc ? 'asc' : 'desc');
    }
}
