<?php

namespace Tests\Feature\Http\Controllers\Api\Projects;

use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexProjectsApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_see_their_projects()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $this->actingAs($project->owner, 'api');
        $this->json('get', '/api/projects')
             ->assertStatus(200)
             ->assertJsonFragment(['name' => $project->name]);
    }

    /** @test */
    public function a_guest_user_cannot_see_projects()
    {
        $project = ProjectFactory::create();

        $this->json('get', '/api/projects')
             ->assertStatus(401);
    }
}
