<?php

namespace App\Console\Commands;

use App\Actions\Globus\GlobusApi;
use Illuminate\Console\Command;

class GlobusAuthTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:globus-auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test to see if globus auth works';

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
     * @return mixed
     */
    public function handle()
    {
        GlobusApi::createGlobusApiWithEcho();
    }
}
