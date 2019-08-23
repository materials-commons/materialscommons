<?php

namespace Tests\Feature\Http\Controllers\Api\Projects;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexProjectsApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_see_their_projects()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\Models\User')->create();
        $project = factory('App\Models\Project')->create([
            'owner_id' => $user->id,
        ]);
        $user->projects()->sync($project);

        $this->actingAs($user, 'api');
        $this->json('get', '/api/projects')
            ->assertStatus(200)
            ->assertJsonFragment(['name' => $project->name]);
    }

    /** @test */
    public function a_guest_user_cannot_see_projects()
    {
        $user = factory('App\Models\User')->create();
        factory('App\Models\Project')->create([
            'owner_id' => $user->id,
        ]);

        $this->json('get', '/api/projects')
            ->assertStatus(401);
    }
}
