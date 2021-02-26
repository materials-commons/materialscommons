<?php

namespace App\Console\Commands\Transfer;

use App\Actions\Globus\GlobusApi;
use App\Models\GlobusTransfer;
use App\Models\TransferRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemoveClosedTransferRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-transfer:remove-closed-transfer-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete closed transfer requests';

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
        TransferRequest::where('state', 'closed')
                       ->get()
                       ->each(function (TransferRequest $transferRequest) use ($globusApi) {
                           $globusTransfer = GlobusTransfer::where('transfer_id', $transferRequest->id)
                                                           ->where('state', 'closed')
                                                           ->get();
                           if (!is_null($globusTransfer)) {
                               try {
                                   $globusApi->deleteEndpointAclRule($globusTransfer->globus_endpoint_id,
                                       $globusTransfer->globus_acl_id);
                               } catch (\Exception $e) {
                                   Log::error("Unable to delete acl");
                               }
                           }
                           $transferRequest->delete();
                       });
        return 0;
    }
}
