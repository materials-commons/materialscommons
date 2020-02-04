<?php

namespace Tests\Utils;

use App\Actions\Globus\GlobusApi;

class GlobusMockUtils
{
    public static function createGlobusApiMock()
    {
        $globusApiMock = \Mockery::mock(GlobusApi::class);
        $globusApiMock->shouldReceive("getIdentities")->andReturns(["identities" => [["id" => "user_id_abc123"]]]);
        $globusApiMock->shouldReceive("addEndpointAclRule")->andReturns(["access_id" => "acl_id_1234"]);
        return $globusApiMock;
    }
}