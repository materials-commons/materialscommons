<?php

namespace App\Livewire\Projects\Datasets\Crud;

use App\Actions\Datasets\DatasetFileSelection;
use App\Actions\Datasets\UpdateDatasetFileSelectionAction;
use App\Http\Controllers\Web\Datasets\Traits\DatasetEntities;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Traits\Table\BaseLivewireTable;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use function blank;
use function explode;

class SelectFiles extends Component
{
    use DatasetEntities;

    public Project $project;
    public Dataset $dataset;

    #[Url(history: true, keep: true)]
    public $directoryId;

    public $currentDir;
    public $dirPaths = [];
    public $filesToSamples;
    public $filesToComputations;

    use WithPagination;
    use BaseLivewireTable;

    public function render()
    {
        $this->filesToSamples = $this->getDatasetFileToEntityMapping($this->dataset, 'experimental');
        $this->filesToComputations = $this->getDatasetFileToEntityMapping($this->dataset, 'computational');
        $this->currentDir = File::find($this->directoryId);
        $this->loadDirPaths();
        $query = $this->getFolderFilesQuery($this->project->id, $this->currentDir->id);
        $query = $this->applySearch($query);
        $query = $this->applySort($query);
        $dir = $this->getDirectory($this->project->id, $this->currentDir->id);
        $files = $this->setSelectedOnFiles($query->paginate(100), $dir);

        return view('livewire.projects.datasets.crud.select-files', [
            'files' => $files,
            'dir'   => $dir,
        ]);
    }

    // Create a list of directory paths from the current directory.
    // The list contains the name of the entry, and the full path. eg:
    // for /dir1/dir2 returns the following:
    // [
    //   ["name" => "", "path" => "/"],
    //   ["name" => "dir1", "path" => "/dir1"],
    //   ["name" => "dir2", "path" => "/dir1/dir2"],
    // ]
    //
    // This is used to construct a list of directories that are links in the
    // view that a user can click on to go to that directory path.
    //
    private function loadDirPaths(): void
    {
        $this->dirPaths = [];
        if ($this->currentDir->path === "/") {
            $this->dirPaths[] = ['name' => '', 'path' => '/'];
        } else {
            $pieces = explode('/', $this->currentDir->path);
            $currentPath = "";
            foreach ($pieces as $piece) {
                if ($piece === "") {
                    $this->dirPaths[] = ['name' => '', 'path' => "/"];
                } else {
                    $currentPath = "{$currentPath}/${piece}";
                    $this->dirPaths[] = ['name' => $piece, 'path' => $currentPath];
                }
            }
        }
    }

    public function toggleSelected($filePath, $fileType, $selected): void
    {
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
        $this->directoryId = $dirId;
    }

    public function gotoDirectoryByPath($path): void
    {
        $dir = File::where('path', $path)
                   ->where('project_id', $this->project->id)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->first();
        $this->directoryId = $dir->id;
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

    public function setSelectedOnFiles($files, $directory)
    {
        $datasetFileSelection = new DatasetFileSelection($this->dataset->file_selection);
        $files->each(function ($file) use ($directory, $datasetFileSelection) {
            if ($file->mime_type === 'directory') {
                $file->selected = $datasetFileSelection->isIncludedDir($file->path);
            } else {
                $filePath = "{$directory->path}/{$file->name}";
                $file->selected = $datasetFileSelection->isIncludedFile($filePath);
            }
        });
        return $files;
    }

    private function getDirectory($projectId, $dir)
    {
        // $dir is either an id, or slash '/' meaning get
        // the root
        if ($dir === '/') {
            return File::where('project_id', $projectId)
                       ->where('current', true)
                       ->whereNull('dataset_id')
                       ->whereNull('deleted_at')
                       ->where('name', '/')->first();
        }

        // $dir is an id
        return File::where('project_id', $projectId)->where('id', $dir)->first();
    }

    private function getFolderFilesQuery($projectId, $folderId)
    {
        return File::with('entities')
                   ->where('project_id', $projectId)
                   ->where('directory_id', $folderId)
                   ->where('current', true);
    }
}
