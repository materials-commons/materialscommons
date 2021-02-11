<?php

namespace App\Console\Commands\Globus;

use App\Models\GlobusRequest;
use Illuminate\Console\Command;

class RemoveClosedGlobusRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-globus:remove-closed-globus-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete closed globus requests';

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
        GlobusRequest::where('state', 'closed')->delete();
        return 0;
    }
}
