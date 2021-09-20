<?php

namespace App\Actions\Datasets;

use App\Actions\Directories\CreateDirectoryAction;
use App\Helpers\PathHelpers;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Traits\GetProjectFiles;
use Ramsey\Uuid\Uuid;

class ImportDatasetIntoProjectAction
{
    use GetProjectFiles;

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

    private function getDirectoryOrCreateIfDoesNotExist(File $rootDir, string $path, Project $project)
    {
        $dir = $this->getDirectory($rootDir->name, $path, $project->id);
        if (!is_null($dir)) {
            return $dir;
        }

        $this->makeDirPathInProject($rootDir, $path, $project);

        return $this->getDirectory($rootDir->name, $path, $project->id);
    }

    private function getDirectory($rootDirName, $path, int $projectId)
    {
        $pathToUse = PathHelpers::normalizePath("/{$rootDirName}$path");
        return File::where('project_id', $projectId)
                   ->where('path', $pathToUse)
                   ->where('mime_type', 'directory')
                   ->where('current', true)
                   ->first();
    }

    /**
     * makeDirPathInProject will break a path into its individual parts and then create the
     * whole chain of directories. It checks if each part already exists.
     * @param  \App\Models\File  $rootDir
     * @param  string  $path
     * @param  \App\Models\Project  $project
     */
    private function makeDirPathInProject(File $rootDir, string $path, Project $project)
    {
        $pathToCreate = "";
        foreach (explode('/', $path) as $pathItem) {
            if (blank($pathItem)) {
                continue;
            }
            $pathToCreate = "{$pathToCreate}/{$pathItem}";
            if ($this->getDirectory($rootDir->name, $pathToCreate, $project->id) == null) {
                $parent = $this->getDirectory($rootDir->name, dirname($pathToCreate), $project->id);
                $this->makeDirInProject($parent, $pathToCreate, $project);
            }
        }
    }

    private function makeDirInProject(File $parentDir, string $path, Project $project)
    {
        $data = [
            'name'         => basename($path),
            'project_id'   => $project->id,
            'directory_id' => $parentDir->id,
        ];

        $createDirectoryAction = new CreateDirectoryAction();
        return $createDirectoryAction->execute($data, $project->owner_id);
    }

    private function isIncludedFile(DatasetFileSelection $datasetFileSelection, File $file)
    {
        if (!$this->isFileEntry($file)) {
            return false;
        }

        $filePath = "{$file->directory->path}/{$file->name}";
        return $datasetFileSelection->isIncludedFile($filePath);
    }

    private function isFileEntry(File $file)
    {
        return $file->mime_type !== 'directory';
    }

    private function importFileIntoDir(File $dir, File $file)
    {
        if (!$file->current) {
            return;
        }
        $usesUuid = blank($file->uses_uuid) ? $file->uuid : $file->uses_uuid;
        $f = $file->replicate()->fill([
            'uuid'         => Uuid::uuid4()->toString(),
            'directory_id' => $dir->id,
            'uses_uuid'    => $usesUuid,
            'owner_id'     => $dir->owner_id,
            'current'      => true,
            'project_id'   => $dir->project_id,
        ]);

        $f->save();
    }
}