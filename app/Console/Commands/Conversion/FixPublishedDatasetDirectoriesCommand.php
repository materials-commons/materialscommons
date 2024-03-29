<?php

namespace App\Console\Commands\Conversion;

use App\Actions\Datasets\CreateDatasetFilesTableAction;
use App\Models\Dataset;
use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use function dirname;

class FixPublishedDatasetDirectoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:fix-published-dataset-directories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix published dataset directories so that the parent (directory_id) is correct.';

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
        //
        // Find all root dirs that are in a published dataset and get a count of the root dirs. If there are more
        // than 1 root dir then that published dataset has multiple directories, so we need to fix that.
        DB::table('files')
          ->select('dataset_id', DB::raw('count(path) as count'))
          ->whereNotNull('dataset_id')
          ->where('path', '/')
          ->groupBy('dataset_id')
          ->get()
          ->each(function ($obj) {
              if ($obj->count > 1) {
                  $this->fixDatasetWithDoubleDirectories($obj->dataset_id);
              }
          });
//        $this->fixDatasetWithDoubleDirectories(155);
//        $this->fixDatasetWithDoubleDirectories(178);

        Dataset::whereNotNull('published_at')
               ->cursor()
               ->each(function (Dataset $dataset) {
                   echo "Processing dataset {$dataset->id}\n";
                   File::where('dataset_id', $dataset->id)
                       ->where('mime_type', 'directory')
                       ->whereNull('directory_id')
                       ->where('path', '<>', '/')
                       ->cursor()->each(function (File $dir) use ($dataset) {
                           $parentDirPath = dirname($dir->path);
                           $parentDir = File::where('dataset_id', $dataset->id)
                                            ->where('path', $parentDirPath)
                                            ->first();
                           if (is_null($parentDir)) {
                               echo "Failed to find parent for {$dir->path} in dataset {$dataset->id}, creating it..\n";
                               echo "  Path searched for '{$parentDirPath}'\n";
                               return;
                           }
                           $dir->update(['directory_id' => $parentDir->id]);
                       });
               });
        return 0;
    }

    private function fixDatasetWithDoubleDirectories($datasetId)
    {
        $dataset = Dataset::findOrFail($datasetId);
        echo "Fixing dataset {$dataset->name}:{$dataset->id} with double directories\n";

        DB::transaction(function () use ($datasetId) {
            File::where('dataset_id', $datasetId)->delete();
        });

        $createDatasetFilesTableAction = new CreateDatasetFilesTableAction();
        $createDatasetFilesTableAction->execute($dataset);
    }
}
