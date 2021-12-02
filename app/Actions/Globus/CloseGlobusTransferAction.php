<?php

namespace App\Actions\Globus;

use App\Models\GlobusTransfer;
use Illuminate\Support\Facades\Log;

class CloseGlobusTransferAction
{
    private GlobusApi $globusApi;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
    }

    public function execute(GlobusTransfer $globusTransfer)
    {
        $globusTransfer->transferRequest()->update(['state' => 'closed']);
        $globusTransfer->update(['state' => 'closed']);

        try {
            $this->globusApi->deleteEndpointAclRule($globusTransfer->globus_endpoint_id,
                $globusTransfer->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }
    }
}