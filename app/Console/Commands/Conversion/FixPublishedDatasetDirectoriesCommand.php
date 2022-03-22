<?php

namespace App\Console\Commands\Conversion;

use App\Models\Dataset;
use App\Models\File;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;
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
        // Hard code in for now the two datasets that need newer directories deleted
        $this->deleteUnusedDirectoriesForPublishedDataset(178, '2021-10-29');
        $this->deleteUnusedDirectoriesForPublishedDataset(155, '2021-10-29');

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
                               $myParentPath = dirname($parentDirPath);
                               $myParentDir = File::where('dataset_id', $dataset->id)
                                                  ->where('path', $myParentPath)
                                                  ->first();
                               if (is_null($myParentDir)) {
                                   echo "  Cannot create....\n";
                                   return;
                               }

                               $parentDir = new File([
                                   'uuid'                   => Uuid::uuid4()->toString(),
                                   'path'                   => $parentDirPath,
                                   'directory_id'           => $myParentDir->id,
                                   'name'                   => basename($parentDir),
                                   'mime_type'              => 'directory',
                                   'current'                => true,
                                   'dataset_id'             => $dataset->id,
                                   'project_id'             => $dataset->project_id,
                                   'owner_id'               => $dataset->owner_id,
                                   'disk'                   => 'mcfs',
                                   'media_type_description' => 'directory',
                               ]);
                               $parentDir->save();
                           }
                           $dir->update(['directory_id' => $parentDir->id]);
                       });
               });
        return 0;
    }

    private function deleteUnusedDirectoriesForPublishedDataset($datasetId, $date)
    {
        File::where('dataset_id', $datasetId)
            ->whereDate('created_at', $date)
            ->where('mime_type', 'directory')
            ->delete();
    }
}
