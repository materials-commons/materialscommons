<?php

namespace App\Console\Commands\Health;

use Illuminate\Console\Command;

class SubmitNextSetOfProjectHealthChecksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-health:submit-next-set-of-project-health-checks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submits the next set of project health checks. Looks at the last time a project was, and also checks last uploads';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
