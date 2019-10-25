<?php

namespace Tests\Feature\Actions\Datasets;

use Tests\TestCase;

class SyncActivitiesToPublishedDatasetActionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
