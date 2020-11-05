<?php

namespace App\Actions\Datasets;

use App\Helpers\PathHelpers;
use App\Models\Dataset;
use App\Traits\GetProjectFiles;
use App\Traits\PathForFile;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

// This is a long running task -
//   Run only in background or as part of an artisan console command

class CreateDatasetZipfileAction
{
    use PathForFile;
    use GetProjectFiles;

    public function __invoke(Dataset $dataset)
    {
        umask(0);

        $datasetDir = $dataset->zipfileDir();
        if (!file_exists($datasetDir)) {
            mkdir($datasetDir, 0777, true);
        }

        $zip = new ZipArchive();
        $zipfile = $dataset->zipfilePath();
        $zip->open($zipfile, ZipArchive::CREATE) or die("Could not open archive");

        $maxFileCountBeforeReopen = 200;
        foreach ($dataset->files()->with('directory')->cursor() as $file) {
            if ($zip->numFiles == $maxFileCountBeforeReopen) {
                echo "close and reopen\n";
                $zip->close();
                $zip->open($zipfile) or die("Error: Could not reopen Zip");
            }

            $uuidPath = $this->getFilePathForFile($file);
            $fullPath = Storage::disk('mcfs')->path("{$uuidPath}");
            $pathInZipfile = PathHelpers::normalizePath("{$dataset->name}/{$file->directory->path}/{$file->name}");
            $zip->addFile($fullPath, $pathInZipfile);
        }
        $zip->close();
        $dataset->update(['zipfile_size' => $dataset->zipfileSize()]);
    }
}
