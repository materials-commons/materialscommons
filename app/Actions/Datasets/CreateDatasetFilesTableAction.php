<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Traits\Datasets\DatasetFileReplication;
use App\Traits\GetProjectFiles;

class CreateDatasetFilesTableAction
{
    use GetProjectFiles;
    use DatasetFileReplication;

    public function execute(Dataset $dataset)
    {
        $datasetFileSelection = new DatasetFileSelection($dataset->file_selection, $dataset);

        $this->clearDatasetFiles($dataset);
        $this->resetReplicatedDirsTracking();

        /** @var  \App\Models\File $file */
        foreach ($this->getCurrentFilesCursorForProject($dataset->project_id) as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $filePath = "{$file->directory->path}/{$file->name}";
            if (!$datasetFileSelection->isIncludedFile($filePath)) {
                continue;
            }
            $f = $this->replicateFile($file, $dataset->id);
            $dataset->files()->attach($f->id);
        }
    }

    private function clearDatasetFiles(Dataset $dataset)
    {
        $dataset->files()->detach();
    }
}
