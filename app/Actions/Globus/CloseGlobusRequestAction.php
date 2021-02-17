<?php

namespace App\Actions\Globus;

use App\Models\GlobusRequest;
use Illuminate\Support\Facades\Log;

class CloseGlobusRequestAction
{
    private GlobusApi $globusApi;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
    }

    public function execute(GlobusRequest $globusRequest)
    {
        $globusRequest->update(['state' => 'closed']);

        try {
            $this->globusApi->deleteEndpointAclRule($globusRequest->globus_endpoint_id, $globusRequest->globus_acl_id);
            $globusRequest->delete();
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }
    }
}