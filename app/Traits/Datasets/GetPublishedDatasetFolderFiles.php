<?php

namespace App\Traits\Datasets;


use App\Models\File;

trait GetPublishedDatasetFolderFiles
{
    public function getPublishedDatasetFolderFiles($dataset, $folder)
    {
        if ($folder == -1) {
            $folder = '/';
        }
        if ($folder == '/') {
            $rootId = File::where('dataset_id', $dataset->id)
                          ->where('name', '/')
                          ->first()->id;
            return File::where('dataset_id', $dataset->id)
                       ->where('directory_id', $rootId)
                       ->whereNull('deleted_at')
                       ->where('current', true)
                       ->get();
        }

        return File::where('dataset_id', $dataset->id)
                   ->where('directory_id', $folder)
                   ->where('current', true)
                   ->whereNull('deleted_at')
                   ->get();
    }
}