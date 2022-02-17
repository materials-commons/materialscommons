<?php

namespace App\Console\Commands\Conversion;

use App\Models\Dataset;
use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddProjectIdToDatasetFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:add-project-id-to-dataset-files';

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
        ini_set("memory_limit", "4096M");

        Dataset::whereNotNull('published_at')->cursor()->each(function(Dataset $dataset) {
            echo "Adding project_id to files for dataset '{$dataset->name}'/{$dataset->id}\n";
            DB::statement("UPDATE files set project_id = {$dataset->project_id} where dataset_id = {$dataset->id}");
        });
        return 0;
    }
}
