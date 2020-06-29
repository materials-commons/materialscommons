<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Traits\GetProjectFiles;

class CreateDatasetFilesTableAction
{
    use GetProjectFiles;

    public function execute(Dataset $dataset)
    {
        $datasetFileSelection = new DatasetFileSelection($dataset->file_selection);

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

            $dataset->files()->attach($file->id);
        }
    }

    private function clearDatasetFiles(Dataset $dataset)
    {
        $dataset->files()->detach();
    }
}