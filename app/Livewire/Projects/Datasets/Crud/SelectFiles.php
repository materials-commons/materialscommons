<?php

namespace App\Livewire\Projects\Datasets\Crud;

use App\Actions\Datasets\GetDatasetFilesAction;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Traits\Table\BaseLivewireTable;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use function blank;

class SelectFiles extends Component
{
    public Project $project;
    public Dataset $dataset;

    #[Url(keep: true)]
    public $directoryId;

    public $currentDir;

    use WithPagination;
    use BaseLivewireTable;

    public function render()
    {
        ray("directoryId: {$this->directoryId}");
        ray("this->project", $this->project);
        $this->currentDir = File::find($this->directoryId);
        ray("this->currentDir", $this->currentDir);
        $getDatasetFilesAction = new GetDatasetFilesAction($this->dataset->file_selection);
        $filesAndDir = $getDatasetFilesAction($this->project->id, $this->currentDir->path);

        return view('livewire.projects.datasets.crud.select-files', [
            'files' => $filesAndDir['files'],
            'dir'   => $filesAndDir['directory'],
        ]);
    }

    #[Renderless]
    public function toggleSelected($fileName, $fileType): void
    {
        ray("toggleFileSelected", $fileName, $fileType);
    }

    public function gotoDirectory($dirId): void
    {
        $this->directoryId = $dirId;
    }

    public function isSelected(File $file): bool
    {
        ray("isSelected", $file);
        if ($file->selected) {
            return true;
        }

        // Here check if file is in a selected sample. For now return false.
        $file->load('entities');
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
