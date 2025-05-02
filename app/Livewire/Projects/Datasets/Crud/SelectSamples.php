<?php

namespace App\Livewire\Projects\Datasets\Crud;

use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Actions\Datasets\UpdateDatasetFileSelectionAction;
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

    public Project $project;
    public Dataset $dataset;
    public $category = 'experimental';
    private $entityFiles;

    public function render()
    {
        return $this->createProjectEntitiesView('livewire.projects.datasets.crud.select-samples');
    }

    public function toggleEntity($entityId): void
    {
        $entity = Entity::findOrFail($entityId);
        $updateDatasetEntitySelectionAction = new UpdateDatasetEntitySelectionAction();
        $updateDatasetEntitySelectionAction->update($entity, $this->dataset);
        $entity->load('files.directory');
        if ($updateDatasetEntitySelectionAction->datasetHasEntity($this->dataset, $entity)) {
            // Add all the files in the entity
            $this->addEntityFilesToDataset($entity);
        } else {
            // Remove all the files in the entity
            $this->removeEntityFilesFromDataset($entity);
        }
    }

    private function addEntityFilesToDataset($entity)
    {
        $updateDatasetFileSelectionAction = new UpdateDatasetFileSelectionAction();
        $entity->files->each(function ($file) use ($updateDatasetFileSelectionAction) {
            if ($file->isDir()) {
                $updateDatasetFileSelectionAction(["include_dir" => $file->path], $this->dataset);
            } else {
                $updateDatasetFileSelectionAction(["include_file" => $file->toPath($file->directory->path)],
                    $this->dataset);
            }
        });
    }

    private function removeEntityFilesFromDataset($entity)
    {
        $updateDatasetFileSelectionAction = new UpdateDatasetFileSelectionAction();
        $entity->files->each(function ($file) use ($updateDatasetFileSelectionAction) {
            if ($file->isDir()) {
                $updateDatasetFileSelectionAction(["remove_include_dir" => $file->path], $this->dataset);
            } else {
                $updateDatasetFileSelectionAction(["remove_include_file" => $file->toPath($file->directory->path)],
                    $this->dataset);
            }
        });
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
