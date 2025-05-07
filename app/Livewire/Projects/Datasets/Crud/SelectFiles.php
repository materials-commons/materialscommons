<?php

namespace App\Livewire\Projects\Datasets\Crud;

use App\Actions\Datasets\GetDatasetFilesAction;
use App\Actions\Datasets\UpdateDatasetFileSelectionAction;
use App\Http\Controllers\Web\Datasets\Traits\DatasetEntities;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Traits\Table\BaseLivewireTable;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use function array_push;
use function blank;
use function explode;
use function ray;

class SelectFiles extends Component
{
    use DatasetEntities;

    public Project $project;
    public Dataset $dataset;

    #[Url(history: true, keep: true)]
    public $directoryId;

    public $currentDir;
    public $dirPaths = [];
    public $sampleFiles;
    public $computationFiles;

    use WithPagination;
    use BaseLivewireTable;

    public function render()
    {
        $this->sampleFiles = $this->getEntitiesForDataset($this->dataset, 'experimental')->flatMap->files->keyBy('id');
        $this->computationFiles = $this->getEntitiesForDataset($this->dataset,
            'computational')->flatMap->files->keyBy('id');
        ray("directoryId: {$this->directoryId}");
        ray("this->project", $this->project);
        $this->currentDir = File::find($this->directoryId);
        ray("this->currentDir", $this->currentDir);
        $this->loadDirPaths();
        $getDatasetFilesAction = new GetDatasetFilesAction($this->dataset->file_selection);
        $filesAndDir = $getDatasetFilesAction($this->project->id, $this->currentDir->id);

        return view('livewire.projects.datasets.crud.select-files', [
            'files' => $filesAndDir['files'],
            'dir'   => $filesAndDir['directory'],
        ]);
    }

    private function loadDirPaths(): void
    {
        $this->dirPaths = [];
        if ($this->currentDir->path === "/") {
            array_push($this->dirPaths, ['name' => '', 'path' => '/']);
        } else {
            $pieces = explode('/', $this->currentDir->path);
            $currentPath = "";
            foreach ($pieces as $piece) {
                if ($piece === "") {
                    array_push($this->dirPaths, ['name' => '', 'path' => "/"]);
                } else {
                    $currentPath = "{$currentPath}/${piece}";
                    array_push($this->dirPaths, ['name' => $piece, 'path' => $currentPath]);
                }
            }
        }
    }

    #[Renderless]
    public function toggleSelected($filePath, $fileType, $selected): void
    {
        $selectedDisplay = var_export($selected, true);
        ray("toggleFileSelected({$filePath}, {$fileType}, {$selectedDisplay})");
        if ($fileType == 'directory') {
            $this->handleToggleDirectory($filePath, $selected);
        } else {
            $this->handleToggleFile($filePath, $selected);
        }
    }

    private function handleToggleDirectory($filePath, $selected): void
    {
        $updateDatasetFileSelectionAction = new UpdateDatasetFileSelectionAction();
        if ($selected) {
            $updateDatasetFileSelectionAction(["remove_include_dir" => $filePath], $this->dataset);
        } else {
            $updateDatasetFileSelectionAction(["include_dir" => $filePath], $this->dataset);
        }
    }

    private function handleToggleFile($filePath, $selected): void
    {
        $updateDatasetFileSelectionAction = new UpdateDatasetFileSelectionAction();
        if ($selected) {
            $updateDatasetFileSelectionAction(["remove_include_file" => $filePath], $this->dataset);
        } else {
            $updateDatasetFileSelectionAction(["include_file" => $filePath], $this->dataset);
        }
    }

    public function gotoDirectory($dirId): void
    {
        ray("gotoDirectory({$dirId})");
        $this->directoryId = $dirId;
    }

    public function gotoDirectoryByPath($path): void
    {
        ray("gotoDirectoryByPath({$path})");
        $dir = File::where('path', $path)
                   ->where('project_id', $this->project->id)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->first();
        $this->directoryId = $dir->id;
    }

    public function isSelected(File $file): bool
    {
        if ($file->selected) {
            return true;
        }

        // Here check if file is in a selected sample. For now return false.
//        $file->load('entities');
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
