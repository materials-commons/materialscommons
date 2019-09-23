<?php

namespace Tests\Feature\Http\Controllers\Api\Experiments;

use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateExperimentApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_experiment()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\Models\User')->create();
        $project = factory('App\Models\Project')->create([
            'owner_id' => $user->id,
        ]);
        $rootDir = factory(File::class)->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);
        $user->projects()->sync($project);

        $this->actingAs($user, 'api');

        $this->json('post', '/api/experiments', [
            'name'       => 'e1',
            'project_id' => $project->id,
        ])
             ->assertStatus(201)
             ->assertJsonFragment(['name' => 'e1']);
    }
}
