<?php

namespace App\Livewire\Projects\Datasets\Crud;

use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Traits\Table\BaseLivewireTable;
use Livewire\Component;
use Livewire\WithPagination;
use function blank;

class SelectFiles extends Component
{
    public Project $project;
    public Dataset $dataset;

    use WithPagination;
    use BaseLivewireTable;

    public function render()
    {
        return view('livewire.projects.datasets.crud.select-files', [
            'files' => collect(),
        ]);
    }

    public function toggleFileSelected(File $file): void
    {

    }

    public function isSelected($file): bool
    {
        if ($file->selected) {
            return true;
        }

        // Here check if file is in a selected sample
        return false;
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
