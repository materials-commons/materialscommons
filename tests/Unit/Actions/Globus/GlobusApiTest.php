<?php

namespace Tests\Unit\Actions\Globus;

use App\Actions\Globus\GlobusApi;
use Tests\TestCase;

class GlobusApiTest extends TestCase
{
    /** @test */
    public function test_authentication()
    {
        $ccUser = env('MC_GLOBUS_CC_USER');
        $ccPassword = env('MC_GLOBUS_CC_TOKEN');
        $globusApi = new GlobusApi($ccUser, $ccPassword);
        $resp = $globusApi->authenticate();
        $this->assertArrayHasKey('access_token', $resp);
        $this->assertArrayHasKey('token_type', $resp);
        $this->assertEquals('Bearer', $resp['token_type']);
    }

    /** @test */
    public function test_get_endpoint_tasks()
    {
        $endpoint = env('MC_GLOBUS_ENDPOINT_ID');
        $globusApi = $this->createApiClient();
        $resp = $globusApi->getEndpointTaskList($endpoint, 7);
        $this->assertArrayHasKey('DATA_TYPE', $resp);
        $this->assertEquals('task_list', $resp['DATA_TYPE']);
    }

    private function createApiClient(): GlobusApi
    {
        $ccUser = env('MC_GLOBUS_CC_USER');
        $ccPassword = env('MC_GLOBUS_CC_TOKEN');
        $globusApi = new GlobusApi($ccUser, $ccPassword);
        $globusApi->authenticate();
        return $globusApi;
    }
}
