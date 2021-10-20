<?php

namespace App\Console\Commands\Conversion;

use App\Models\Activity;
use App\Models\Dataset;
use App\Models\File;
use App\Traits\Datasets\DatasetFileReplication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPublishedDatasetsCommand extends Command
{
    use DatasetFileReplication;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:fix-published-datasets {--dataset= : Dataset to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixes existing published datasets to add dataset_id and replicate directories';

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
        ini_set("memory_limit", "4096M");
        $dsId = $this->option("dataset");
        if (!is_null($dsId)) {
            $this->updateDataset(Dataset::findOrFail($dsId));
        } else {
            $this->info("Converting all published datasets...\n");
            foreach (Dataset::whereNotNull('published_at')->cursor() as $dataset) {
                $this->info("Converting dataset({$dataset->id}): {$dataset->name}");
                $this->updateDataset($dataset);
            }
        }
        return 0;
    }

    private function updateDataset(Dataset $dataset)
    {
        $this->updateDatasetFilesAndDirs($dataset);
//        $this->updateDatasetActivities($dataset);
    }

    private function updateDatasetFilesAndDirs(Dataset $dataset)
    {
        $this->resetReplicatedDirsTracking();
        $this->info("   Updating file dataset_id...");
        $dataset->load('files.directory');
        $dataset->files()->update(['files.dataset_id' => $dataset->id]);
        $this->info("   Done.");
        $this->info("   Replicating directories...");
        $uniqueDirs = $this->getUniqueDirs($dataset);
        foreach ($uniqueDirs as $d) {
            $dir = $this->lookupReplicatedDirByPath($d->path);
            if (is_null($dir)) {
                $this->replicateDirectoryTree($d, $dataset->id);
            }
        }
        foreach ($uniqueDirs as $d) {
            $replicatedDir = $this->lookupReplicatedDirByPath($d->path);
            if (is_null($replicatedDir)) {
                $this->error("    Couldn't find replicated dir path {$d->path}");
                continue;
            }
            File::where('directory_id', $d->id)
                ->where('dataset_id', $dataset->id)
                ->update(['directory_id' => $replicatedDir->id]);
        }
        $this->info("   Done.");
        $this->info("");
    }

    private function getUniqueDirs(Dataset $dataset)
    {
        return File::whereIn(
            'id',
            DB::table('files')
              ->select('directory_id')
              ->where('dataset_id', $dataset->id)
              ->whereNotNull('directory_id')
              ->distinct()
        )->get();
    }

    private function updateDatasetActivities(Dataset $dataset)
    {
        $dataset->load(['activities.entityStates', 'entities']);

        $this->info("   Updating activities...");
        $dataset->activities()->update(['activities.dataset_id' => $dataset->id]);
        $this->info("   Done.");

        $this->info("   Updating entities...");
        $dataset->entities()->update(['entities.dataset_id' => $dataset->id]);
        $this->info("   Done.");

        $this->info("   Updating entityStatess for all activities...");
        $dataset->activities->each(function (Activity $activity) use ($dataset) {
            $activity->entityStates()->update(['entityStates.dataset_id' => $dataset->id]);
        });
        $this->info("   Done.");
    }
}
