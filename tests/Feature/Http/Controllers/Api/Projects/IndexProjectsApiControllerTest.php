<?php

namespace Tests\Feature\Http\Controllers\Api\Projects;

use App\Models\Project;
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
        $allProjects = Project::all();

        $this->actingAs($project->owner, 'api');
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
