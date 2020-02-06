<?php

namespace Tests\Unit\Actions\Globus;

use App\Actions\Globus\GlobusApi;
use Tests\TestCase;

class GlobusApiTest extends TestCase
{
    /** @test */
    public function test_authentication()
    {
        $ccUser = config('globus.cc_user');
        $ccPassword = config('globus.cc_token');
        $globusApi = new GlobusApi($ccUser, $ccPassword);
        $resp = $globusApi->authenticate();
        $this->assertArrayHasKey('access_token', $resp);
        $this->assertArrayHasKey('token_type', $resp);
        $this->assertEquals('Bearer', $resp['token_type']);
    }

    /** @test */
    public function test_get_endpoint_tasks()
    {
        $endpoint = config('globus.endpoint');
        $globusApi = $this->createApiClient();
        $resp = $globusApi->getEndpointTaskList($endpoint, 7);
        $this->assertArrayHasKey('DATA_TYPE', $resp);
        $this->assertEquals('task_list', $resp['DATA_TYPE']);
    }

    /** @test */
    public function test_get_identities()
    {
        $globusApi = $this->createApiClient();
        $resp = $globusApi->getIdentities(["gtarcea@umich.edu"]);
        $this->assertArrayHasKey("identities", $resp);
        $this->assertEquals(1, sizeof($resp["identities"]));
        $this->assertArrayHasKey("id", $resp["identities"][0]);
    }

    private function createApiClient(): GlobusApi
    {
        $ccUser = config('globus.cc_user');
        $ccPassword = config('globus.cc_token');
        $globusApi = new GlobusApi($ccUser, $ccPassword);
        $globusApi->authenticate();
        return $globusApi;
    }
}
