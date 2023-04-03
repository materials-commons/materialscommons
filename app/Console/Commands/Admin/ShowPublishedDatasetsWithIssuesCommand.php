<?php

namespace App\Console\Commands\Admin;

use App\Models\Dataset;
use Illuminate\Console\Command;
use function blank;

class ShowPublishedDatasetsWithIssuesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-admin:show-published-datasets-with-issues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show datasets that have issues';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $published = Dataset::whereNotNull('published_at')->get();

        $this->datasetsWithNoFiles($published);
        $this->datasetsWithNoZipfile($published);
        $this->datasetsWithNoGlobusFiles($published);

        return Command::SUCCESS;
    }

    private function datasetsWithNoFiles($datasets)
    {
        $matches = $datasets->filter(function (Dataset $ds) {
            return $ds->files()->count() == 0;
        });

        if ($matches->count() != 0) {
            echo "Datasets with zero files:\n";
            $this->table(['ID', 'Name', 'Created At'], $this->pluckColumnData($matches));
        }
    }

    private function datasetsWithNoZipfile($datasets)
    {
        $matches = $datasets->filter(function (Dataset $ds) {
            return $ds->zipfile_size == 0;
        });

        if ($matches->count() != 0) {
            echo "\n\nDatasets with no zipfile:\n";
            $this->table(['ID', 'Name', 'Created At'], $this->pluckColumnData($matches));
        }
    }

    private function datasetsWithNoGlobusFiles($datasets)
    {
        $matches = $datasets->filter(function (Dataset $ds) {
            return $ds->globus_path_exists == 0;
        });

        if ($matches->count() != 0) {
            echo "\n\nDatasets with no globus path:\n";
            $this->table(['ID', 'Name', 'Created At'], $this->pluckColumnData($matches));
        }
    }

    private function pluckColumnData($matches)
    {
        return $matches->transform(function ($ds) {
            return [$ds->id, $ds->name, $ds->created_at];
        });
    }
}
