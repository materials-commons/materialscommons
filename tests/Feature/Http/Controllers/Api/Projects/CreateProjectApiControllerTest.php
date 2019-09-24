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

    /** @test */
    public function it_should_return_an_existing_project()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\Models\User')->create();
        $project = factory('App\Models\Project')->create([
            'owner_id' => $user->id,
            'name'     => 'p1',
        ]);
        $this->actingAs($user, 'api');
        $this->json('post', '/api/projects', [
            'name' => 'p1',
        ])
             ->assertStatus(200)
             ->assertJsonFragment(['name' => 'p1']);
    }
}
