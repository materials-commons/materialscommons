<?php

namespace Tests\Feature\Http\Controllers\Api\Etl;

use Tests\TestCase;

class AddMeasurementsToSampleInProcessApiControllerTest extends TestCase
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
