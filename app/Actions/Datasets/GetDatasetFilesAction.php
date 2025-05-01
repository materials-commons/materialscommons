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
        ray("getDirectory({$projectId}, {$dir})");
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

    private function getFolderFiles($projectId, $folderId)
    {
        return File::with('entities')
                   ->where('project_id', $projectId)
                   ->where('directory_id', $folderId)
                   ->where('current', true)
                   ->get();
    }
}