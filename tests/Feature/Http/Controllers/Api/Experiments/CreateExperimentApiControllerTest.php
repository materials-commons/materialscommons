<?php

namespace Tests\Feature\Http\Controllers\Api\Experiments;

use Tests\TestCase;

class CreateExperimentApiControllerTest extends TestCase
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
