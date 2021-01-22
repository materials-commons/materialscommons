<?php

namespace App\Console\Commands;

use App\Actions\Datasets\DatasetFileSelection;
use App\Helpers\PathHelpers;
use App\Models\Dataset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateZipfileForUnpublishedDatasetCommand extends Command
{
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
        $datasetFileSelection = new DatasetFileSelection($dataset->file_selection);

        $datasetDir = $dataset->zipfileDir();
        if (!file_exists($datasetDir)) {
            mkdir($datasetDir, 0777, true);
        }

        $zip = new ZipArchive();
        $zipfile = $dataset->zipfilePath();
        $zip->open($zipfile, ZipArchive::CREATE) or die("Could not open archive");

        $maxFileCountBeforeReopen = 200;

        foreach ($this->getCurrentFilesCursorForProject($dataset->project_id) as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $filePath = "{$file->directory->path}/{$file->name}";
            if (!$datasetFileSelection->isIncludedFile($filePath)) {
                continue;
            }

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
        echo "Created zipfile at {$dataset->zipfilePath()}\n";

        return 0;
    }
}
