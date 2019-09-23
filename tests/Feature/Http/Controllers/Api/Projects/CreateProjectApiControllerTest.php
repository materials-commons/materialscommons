<?php

namespace Tests\Feature\Http\Controllers\Api\Projects;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProjectApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_project()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');
        $this->json('post', '/api/projects', [
            'name' => 'p1',
        ])
             ->assertStatus(201)
             ->assertJsonFragment(['name' => 'p1']);
    }
}
