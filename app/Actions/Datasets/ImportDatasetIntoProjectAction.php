<?php

namespace App\Actions\Datasets;

use App\Actions\Directories\CreateDirectoryAction;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Traits\CopyFiles;
use App\Traits\GetProjectFiles;

class ImportDatasetIntoProjectAction
{
    use GetProjectFiles;
    use CopyFiles;

    /**
     * @var CreateDirectoryAction
     */
    private $createDirectoryAction;

    public function __construct()
    {
        $this->createDirectoryAction = new CreateDirectoryAction();
    }

    public function execute(Dataset $dataset, Project $project, $rootDirName)
    {
        $this->importDatasetFilesIntoProject($dataset, $project, $rootDirName);
        $project->importedDatasets()->attach($dataset);

        return true;
    }

    private function importDatasetFilesIntoProject(Dataset $dataset, Project $project, $rootDirName)
    {
        $rootDir = $this->createRootDirForDataset($rootDirName, $project);
        $dsFileSelection = new DatasetFileSelection($dataset->file_selection);
        foreach ($this->getFilesCursorForProject($dataset->project_id) as $file) {
            if ($this->isIncludedFile($dsFileSelection, $file)) {
                if ($this->canImportFile($dataset, $file)) {
                    $dir = $this->getDirectoryOrCreateIfDoesNotExist($rootDir, $file->directory->path, $project);
                    $this->importFileIntoDir($dir, $file);
                }
            }
        }
    }

    private function createRootDirForDataset($rootDirName, Project $project)
    {
        $projectRootDir = $project->rootDir()->first();
        $data = [
            'name'         => $rootDirName,
            'project_id'   => $project->id,
            'directory_id' => $projectRootDir->id,
        ];

        return $this->createDirectoryAction->execute($data, $project->owner_id);
    }

    private function canImportFile($dataset, $file)
    {
        if (is_null($dataset->published_at)) {
            // Dataset not published, so only import current files
            return $file->current;
        }

        // Published dataset only import files that are in the dataset
        return $dataset->hasFile($file->id);
    }

    private function isIncludedFile(DatasetFileSelection $datasetFileSelection, File $file): bool
    {
        if (!$this->isFileEntry($file)) {
            return false;
        }

        $filePath = "{$file->directory->path}/{$file->name}";
        return $datasetFileSelection->isIncludedFile($filePath);
    }
}