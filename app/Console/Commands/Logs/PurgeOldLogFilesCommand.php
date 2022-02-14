<?php

namespace App\Console\Commands\Logs;

use Illuminate\Console\Command;

class PurgeOldLogFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-logs:purge-old-log-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purges old log files';

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
