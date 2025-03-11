<?php

namespace App\Livewire\Projects\Datasets\Crud;

use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Http\Controllers\Web\Datasets\Traits\HasExtendedInfo;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Project;
use App\Traits\Entities\EntitiesTableViewBuilder;
use App\Traits\Table\BaseLivewireTable;
use Livewire\Component;
use Livewire\WithPagination;
use function blank;

class SelectSamples extends Component
{
    use EntitiesTableViewBuilder;
    use WithPagination;
    use BaseLivewireTable;
    use HasExtendedInfo;

    public Project $project;
    public Dataset $dataset;
    public $category = 'experimental';
    private $datasetEntities;

    public function render()
    {
        $this->datasetEntities = $this->getEntitiesForDataset($this->dataset);
        return $this->createProjectEntitiesView('livewire.projects.datasets.crud.select-samples');
    }

    public function toggleEntity($entityId): void
    {
        $entity = Entity::findOrFail($entityId);
        $updateDatasetEntitySelectionAction = new UpdateDatasetEntitySelectionAction();
        $updateDatasetEntitySelectionAction($entity, $this->dataset);
    }

    public function entityInDataset($entityId)
    {
        return $this->datasetEntities->contains($entityId);
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
