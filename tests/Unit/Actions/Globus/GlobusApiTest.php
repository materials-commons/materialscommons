<?php

namespace Tests\Unit\Actions\Globus;

use App\Actions\Globus\GlobusApi;
use Tests\TestCase;

class GlobusApiTest extends TestCase
{
    /** @test */
    public function test_authentication()
    {
        $ccUser = 'd8eba3ad-fdd9-468d-95a1-d5c4ff91de3f';
        $ccPassword = 'K3DWTaRt2MT9QzYA+Ttxs++9kea0cUO219wzaElQqP4=';
        $globusApi = new GlobusApi($ccUser, $ccPassword);
        $resp = $globusApi->authenticate();
        $this->assertArrayHasKey('access_token', $resp);
        $this->assertArrayHasKey('token_type', $resp);
        $this->assertEquals('Bearer', $resp['token_type']);
    }

    /** @test */
    public function test_get_endpoint_tasks()
    {
        $endpoint = '2c8eb4de-c64c-11e8-8c33-0a1d4c5c824a';
        $globusApi = $this->createApiClient();
        $resp = $globusApi->getEndpointTaskList($endpoint, 7);
        $this->assertArrayHasKey('DATA_TYPE', $resp);
        $this->assertEquals('task_list', $resp['DATA_TYPE']);
    }

    private function createApiClient(): GlobusApi
    {
        $ccUser = 'd8eba3ad-fdd9-468d-95a1-d5c4ff91de3f';
        $ccPassword = 'K3DWTaRt2MT9QzYA+Ttxs++9kea0cUO219wzaElQqP4=';
        $globusApi = new GlobusApi($ccUser, $ccPassword);
        $globusApi->authenticate();
        return $globusApi;
    }
}
