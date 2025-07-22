<?php

namespace App\Actions\Datasets;

use App\Models\File;

class GetDatasetFilesAction
{
    private $datasetFileSelection;

    public function __construct($selection)
    {
        $this->datasetFileSelection = new DatasetFileSelection($selection);
    }

    public function __invoke($projectId, $dir)
    {
        $directory = $this->getDirectory($projectId, $dir);
        $files = $this->getFolderFiles($projectId, $directory->id);
        $files->each(function ($file) use ($directory) {
            if ($file->mime_type === 'directory') {
                $file->selected = $this->datasetFileSelection->isIncludedDir($file->path);
            } else {
                $filePath = "{$directory->path}/{$file->name}";
                $file->selected = $this->datasetFileSelection->isIncludedFile($filePath);
            }
        });
        return ['files' => $files, 'directory' => $directory];
    }

    private function getDirectory($projectId, $dir)
    {
        // $dir is either an id, or slash '/' meaning get
        // the root
        if ($dir === '/') {
            return File::where('project_id', $projectId)
                       ->whereNull('dataset_id')
                       ->where('name', '/')
                       ->first();
        }

        // $dir is an id
        return File::where('project_id', $projectId)->where('id', $dir)->first();
    }

    private function getFolderFiles($projectId, $folderId)
    {
        return File::where('project_id', $projectId)
                   ->where('directory_id', $folderId)
                   ->whereNull('deleted_at')
                   ->whereNull('dataset_id')
                   ->where('current', true)
                   ->get();
    }
}