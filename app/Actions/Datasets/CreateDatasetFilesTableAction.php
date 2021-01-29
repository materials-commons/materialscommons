<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Models\File;
use App\Traits\GetProjectFiles;
use Ramsey\Uuid\Uuid;

class CreateDatasetFilesTableAction
{
    use GetProjectFiles;

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
            $f = $this->duplicateFile($file);
            $dataset->files()->attach($f->id);
        }
    }

    private function clearDatasetFiles(Dataset $dataset)
    {
        $dataset->files()->detach();
    }

    private function duplicateFile(File $file)
    {
        $f = $file->replicate(['project_id'])->fill([
            'uuid'      => Uuid::uuid4()->toString(),
            'uses_uuid' => blank($file->uses_uuid) ? $file->uuid : $file->uses_uuid,
        ]);
        $f->save();
        return $f->refresh();
    }
}