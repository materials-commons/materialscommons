<?php

namespace App\Actions\Globus;

use App\Models\GlobusRequest;

class CloseGlobusRequestAction
{
//    private GlobusApi $globusApi;
//
//    public function __construct(GlobusApi $globusApi)
//    {
//        $this->globusApi = $globusApi;
//    }

    public function execute(GlobusRequest $globusRequest)
    {
//        try {
//            $this->globusApi->deleteEndpointAclRule($globusRequest->globus_endpoint_id, $globusRequest->globus_acl_id);
//        } catch (\Exception $e) {
//            Log::error("Unable to delete acl");
//        }

        $globusRequest->update(['state' => 'closed']);
    }
}