<?php

namespace App\Console\Commands\Conversion;

use App\Models\Dataset;
use App\Models\Entity;
use App\Models\File;
use Illuminate\Console\Command;

class AddEntityFilesToDatasetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:add-entity-files-to-dataset {dataset : dataset id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $dataset = Dataset::findOrFail($this->argument("dataset"));
        $this->addDatasetEntityFilesToDataset($dataset);
        return 0;
    }

    public function addDatasetEntityFilesToDataset(Dataset $dataset)
    {
        $entities = $dataset->entitiesFromTemplate();
        $datasetFileSelection = $dataset->file_selection;
        $entities->each(function (Entity $entity) use (&$datasetFileSelection) {
            echo "Loading files for entity {$entity->name}\n";
            $entity->load('files.directory');
            $filesCount = $entity->files()->count();
            echo "  with files count of {$filesCount}\n";
            $entity->files->each(function (File $file) use (&$datasetFileSelection) {
                $fileDirPath = $file->directory->path === "/" ? "" : $file->directory->path;
                $filePath = "{$fileDirPath}/{$file->name}";
                echo "  adding file {$filePath}\n";
                if (!in_array($filePath, $datasetFileSelection['include_files'])) {
                    array_push($datasetFileSelection['include_files'], $filePath);
                }
            });
        });
        $dataset->update(['file_selection' => $datasetFileSelection]);
    }
}
