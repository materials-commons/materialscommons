<?php

namespace Tests\Feature\Actions\Etl;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateFromJsonActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
