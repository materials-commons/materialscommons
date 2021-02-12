<?php

namespace App\Console\Commands\Globus;

use App\Actions\Globus\GlobusApi;
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
        $globusApi = GlobusApi::createGlobusApi();
        GlobusRequest::where('state', 'closed')
                     ->get()
                     ->each(function (GlobusRequest $globusRequest) use ($globusApi) {
                         try {
                             $globusApi->deleteEndpointAclRule($globusRequest->globus_endpoint_id,
                                 $globusRequest->globus_acl_id);
                         } catch (\Exception $e) {
                             Log::error("Unable to delete acl");
                         }
                         $globusRequest->delete();
                     });
        return 0;
    }
}
