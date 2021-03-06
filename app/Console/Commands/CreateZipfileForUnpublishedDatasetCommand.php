<?php

namespace App\Console\Commands;

use App\Actions\Datasets\DatasetFileSelection;
use App\Helpers\PathHelpers;
use App\Models\Dataset;
use App\Traits\GetProjectFiles;
use App\Traits\PathForFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class CreateZipfileForUnpublishedDatasetCommand extends Command
{
    use PathForFile;
    use GetProjectFiles;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:create-unpublished-dataset-zipfile {dataset : id of dataset to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a zipfile for an unpublished dataset';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        umask(0);
        $dataset = Dataset::findOrFail($this->argument('dataset'));
        $datasetFileSelection = new DatasetFileSelection($dataset->file_selection, $dataset);

        if (Storage::disk('mcfs')->exists($dataset->zipfilePathPartial())) {
            Storage::disk('mcfs')->delete($dataset->zipfilePathPartial());
        }

        $datasetDir = $dataset->zipfileDir();
        if (!file_exists($datasetDir)) {
            mkdir($datasetDir, 0777, true);
        }

        $zip = new ZipArchive();
        $zipfile = $dataset->zipfilePath();
        $zip->open($zipfile, ZipArchive::CREATE) or die("Could not open archive");

        $maxFileCountBeforeReopen = 200;
        $fileCount = 0;

        foreach ($this->getCurrentFilesCursorForProject($dataset->project_id) as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $filePath = "{$file->directory->path}/{$file->name}";
//            echo "Checking file {$filePath}\n";
            if (!$datasetFileSelection->isIncludedFile($filePath)) {
//                echo "   Not included...\n";
                continue;
            }

//            if ($zip->numFiles == $maxFileCountBeforeReopen) {
//                echo "close and reopen\n";
//                $zip->close();
//                echo "past close\n";
//                $zip->open($zipfile) or die("Error: Could not reopen Zip");
//            }

            $uuidPath = $this->getFilePathForFile($file);
            $fullPath = Storage::disk('mcfs')->path("{$uuidPath}");
//            echo "   Adding to zipfile ${fullPath}...\n";
            $fileCount++;
            if ($fileCount % 100 == 0) {
                echo "Added {$fileCount} files to zipfile...\n";
            }
            $pathInZipfile = PathHelpers::normalizePath("{$dataset->name}/{$file->directory->path}/{$file->name}");
            $zip->addFile($fullPath, $pathInZipfile);
        }
        $zip->close();

        $dataset->update(['zipfile_size' => $dataset->zipfileSize()]);
        echo "Created zipfile at {$dataset->zipfilePath()}\n";

        return 0;
    }
}
