<?php

namespace App\Livewire\Datasets;

use App\Actions\Datasets\UpdateDatasetFileSelectionAction;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Services\AuthService;
use Illuminate\Support\Collection;
use Livewire\Component;

class FileSelection extends Component
{
    public Project $project;
    public Dataset $dataset;
    public File $directory;

    public string $directoryPathRouteName;
    public string $addFilesRouteName;
    public string $createDirectoryRouteName;

    public Collection $files;
    public array $dirPaths = [];

    public int $totalSelectedCount = 0;

    public function mount(
        Project $project,
        Dataset $dataset,
        File $directory,
        Collection $files,
        array $dirPaths,
        string $directoryPathRouteName,
        string $addFilesRouteName,
        string $createDirectoryRouteName
    ): void {
        abort_unless(AuthService::userCanAccessProjectId(auth()->user(), $project->id), 404, 'No such project');
        abort_unless($dataset->project_id === $project->id, 404, 'No such dataset');
        abort_unless($directory->project_id === $project->id, 404, 'No such directory');

        $this->project = $project;
        $this->dataset = $dataset;
        $this->directory = $directory;
        $this->files = $files;
        $this->dirPaths = $dirPaths;
        $this->directoryPathRouteName = $directoryPathRouteName;
        $this->addFilesRouteName = $addFilesRouteName;
        $this->createDirectoryRouteName = $createDirectoryRouteName;

        $fileCount = isset($dataset->file_selection['include_files'])
            ? count($dataset->file_selection['include_files'])
            : 0;

        $dirCount = isset($dataset->file_selection['include_dirs'])
            ? count($dataset->file_selection['include_dirs'])
            : 0;

        $this->totalSelectedCount = $fileCount + $dirCount;
    }

    public function toggleFile(int $fileId): void
    {
        $file = File::findOrFail($fileId);

        abort_unless($file->project_id === $this->project->id, 404, 'No such file');

        if ($file->mime_type === 'directory') {
            $this->toggleDirectorySelection($file);
            return;
        }

        $this->toggleRegularFileSelection($file);
    }

    private function toggleDirectorySelection(File $directory): void
    {
        $dataset = $this->dataset->fresh();

        $includeDirs = collect($dataset->file_selection['include_dirs'] ?? []);

        $payload = $includeDirs->contains($directory->path)
            ? ['project_id' => $this->project->id, 'remove_include_dir' => $directory->path]
            : ['project_id' => $this->project->id, 'include_dir' => $directory->path];

        $this->updateDatasetFileSelection($payload);
    }

    private function toggleRegularFileSelection(File $file): void
    {
        $dataset = $this->dataset->fresh();

        $directoryPath = $this->directory->path === '/' ? '' : $this->directory->path;
        $filePath = "{$directoryPath}/{$file->name}";

        $includeFiles = collect($dataset->file_selection['include_files'] ?? []);

        $payload = $includeFiles->contains($filePath)
            ? ['project_id' => $this->project->id, 'remove_include_file' => $filePath]
            : ['project_id' => $this->project->id, 'include_file' => $filePath];

        $this->updateDatasetFileSelection($payload);
    }

    private function updateDatasetFileSelection(array $payload): void
    {
        $action = new UpdateDatasetFileSelectionAction();

        $this->dataset = $action($payload, $this->dataset->fresh());
        $this->dataset->globus_acl_id = null;

        $fileCount = isset($this->dataset->file_selection['include_files'])
            ? count($this->dataset->file_selection['include_files'])
            : 0;

        $dirCount = isset($this->dataset->file_selection['include_dirs'])
            ? count($this->dataset->file_selection['include_dirs'])
            : 0;

        $this->totalSelectedCount = $fileCount + $dirCount;
    }

    public function render()
    {
        return view('livewire.datasets.file-selection');
    }
}
