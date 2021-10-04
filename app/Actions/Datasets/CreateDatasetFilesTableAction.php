<?php

namespace App\Actions\Datasets;

use App\Helpers\PathHelpers;
use App\Models\Dataset;
use App\Models\File;
use App\Traits\GetProjectFiles;
use Ramsey\Uuid\Uuid;

class CreateDatasetFilesTableAction
{
    use GetProjectFiles;

    // Track existing directories that we have replicated by path
    private array $replicatedDirectories;

    public function __construct()
    {
        $this->replicatedDirectories = [];
    }

    public function execute(Dataset $dataset)
    {
        $datasetFileSelection = new DatasetFileSelection($dataset->file_selection, $dataset);

        $this->clearDatasetFiles($dataset);

        /** @var  \App\Models\File $file */
        foreach ($this->getCurrentFilesCursorForProject($dataset->project_id) as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $filePath = "{$file->directory->path}/{$file->name}";
            if (!$datasetFileSelection->isIncludedFile($filePath)) {
                continue;
            }
            $f = $this->duplicateFile($file, $dataset->id);
            $dataset->files()->attach($f->id);
        }
    }

    private function clearDatasetFiles(Dataset $dataset)
    {
        $dataset->files()->detach();
    }

    private function duplicateFile(File $file, $datasetId)
    {
        if (array_key_exists($file->directory->path, $this->replicatedDirectories)) {
            $directory = $this->replicatedDirectories[$file->directory->path];
        } else {
            $directory = $this->replicateDirectoryTree($file->directory, $datasetId);
        }

        $f = $file->replicate(['project_id'])->fill([
            'uuid'         => Uuid::uuid4()->toString(),
            'uses_uuid'    => blank($file->uses_uuid) ? $file->uuid : $file->uses_uuid,
            'directory_id' => $directory->id,
            'dataset_id'   => $datasetId,
        ]);
        $f->save();
        return $f->refresh();
    }

    // Replicates the tree for this directory, and returns the child node that we were asked to create.
    private function replicateDirectoryTree(File $dir, $datasetId): File
    {
        // Need replicate all the way up the tree
        $path = "/";
        $lastDir = null;
        $parentId = null;
        foreach (explode("/", $dir->path) as $pathEntry) {
            $path = PathHelpers::joinPaths($path, $pathEntry);
            if (array_key_exists($path, $this->replicatedDirectories)) {
                $lastDir = $this->replicatedDirectories[$path];
            } else {
                $existingDir = File::getDirectoryByPath($dir->project_id, $path);
                $lastDir = $this->createReplicatedDir($existingDir, $parentId, $datasetId);
                $parentId = $lastDir->id;
                $this->replicatedDirectories[$path] = $lastDir;
            }
        }

        return $lastDir;
    }

    private function createReplicatedDir(File $dir, $parentId, $datasetId): File
    {
        $d = $dir->replicate(['project_id'])->fill([
            'uuid'         => Uuid::uuid4()->toString(),
            'directory_id' => $parentId,
            'dataset_id'   => $datasetId,
        ]);

        $d->save();
        return $d->refresh();
    }
}
