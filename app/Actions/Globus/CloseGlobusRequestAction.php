<?php

namespace App\Actions\Globus;

use App\Models\GlobusRequest;
use Illuminate\Support\Facades\Log;

class CloseGlobusRequestAction
{
    public function execute(GlobusRequest $globusRequest)
    {
        try {
            $globusApi = GlobusApi::createGlobusApi();
            $globusApi->deleteEndpointAclRule($globusRequest->globus_endpoint_id, $globusRequest->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        $globusRequest->update(['state' => 'closed']);
    }
}