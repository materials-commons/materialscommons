<?php

namespace App\Actions\Datasets;

use App\Helpers\PathHelpers;
use App\Models\Dataset;
use App\Models\File;
use App\Traits\PathForFile;
use ZipArchive;

// This is a long running task -
//   Run only in background or as part of an artisan console command

class CreateDatasetZipfileAction
{
    use PathForFile;

    public function __invoke($datasetId, $createDatasetFilesTable)
    {
        $dataset = Dataset::find($datasetId);

        if ($createDatasetFilesTable) {
            $dataset->files()->detach();
        }

        $datasetDir = storage_path("app/__datasets/{$dataset->uuid}");
        if (!file_exists($datasetDir)) {
            mkdir($datasetDir, 0700, true);
        }

        $zip = new ZipArchive();
        $zipfile = "{$datasetDir}/{$dataset->name}.zip";
        $datasetFileSelection = new DatasetFileSelection($dataset->file_selection);
        $zip->open($zipfile, ZipArchive::CREATE) or die("Could not open archive");

        $maxFileCountBeforeReopen = 200;
        foreach (File::with('directory')->where('project_id', $dataset->project_id)->cursor() as $file) {
            if ($this->isFileEntry($file)) {
                $filePath = "{$file->directory->path}/{$file->name}";
                if ($datasetFileSelection->isIncludedFile($filePath)) {
                    if ($zip->numFiles == $maxFileCountBeforeReopen) {
                        $zip->close();
                        $zip->open($zipfile) or die("Error: Could not reopen Zip");
                    }

                    if ($createDatasetFilesTable) {
                        $dataset->files()->attach($file->id);
                    }

                    $uuid = $file->uses_uuid ?? $file->uuid;
                    $uuidPath = $this->getFilePathForFile($uuid);
                    $fullPath = storage_path("app/{$uuidPath}");
                    $pathInZipfile = PathHelpers::normalizePath("{$dataset->name}/{$file->directory->path}/{$file->name}");
                    $zip->addFile($fullPath, $pathInZipfile);
                }
            }
        }
        $zip->close();
    }

    private function isFileEntry(File $file)
    {
        return $file->mime_type !== 'directory';
    }
}