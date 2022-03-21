<?php

namespace App\Console\Commands\Conversion;

use Illuminate\Console\Command;

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
        return 0;
    }
}
