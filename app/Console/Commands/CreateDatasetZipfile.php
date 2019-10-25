<?php

namespace App\Console\Commands;

use App\Actions\Datasets\DatasetFileSelection;
use App\Helpers\PathHelpers;
use App\Models\Dataset;
use App\Models\File;
use App\Traits\PathFromUUID;
use Illuminate\Console\Command;
use ZipArchive;

class CreateDatasetZipfile extends Command
{
    use PathFromUUID;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:create-dataset-zipfile {dataset : id of dataset to create} {--create-dataset-files-table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the zipfile for a dataset';

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
     * @return mixed
     */
    public function handle()
    {
        $dataset = Dataset::find($this->argument('dataset'));
        $createDatasetFilesTable = $this->option('create-dataset-files-table');

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
                    $uuidPath = $this->filePathFromUuid($uuid);
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
