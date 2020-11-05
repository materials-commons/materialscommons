<?php

namespace App\Console\Commands\Conversion;

use App\Models\Dataset;
use Illuminate\Console\Command;

class UpdateAllDatasetsZipAndGlobusColumnsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:update-all-datasets-zip-and-globus-columns';

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
        Dataset::all()->each(function (Dataset $dataset) {
            if (is_null($dataset->published_at)) {
                echo "Updating unpublished dataset {$dataset->name}/{$dataset->id}\n";
                $dataset->update([
                    'zipfile_size'       => 0,
                    'globus_path_exists' => false,
                ]);
            } else {
                echo "Updating published dataset {$dataset->name}/{$dataset->id}\n";
                $dataset->update([
                    'zipfile_size'       => $dataset->zipfileSize(),
                    'globus_path_exists' => file_exists($dataset->publishedGlobusPath()),
                ]);
            }
        });

        return 0;
    }
}
