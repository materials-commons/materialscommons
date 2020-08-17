<?php

namespace Tests\Feature\Http\Controllers\Api\Etl;

use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateExperimentProgressStatusApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_update_experiment_status()
    {
        $this->withoutExceptionHandling();

        $user = factory('App\Models\User')->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $experiment = factory('App\Models\Experiment')->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $this->actingAs($user, 'api');

        $this->json('post', '/api/etl/updateExperimentProgressStatus', [
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
            'loading'       => true,
        ])
             ->assertStatus(200)
             ->assertJsonFragment(['success' => true]);
        $this->assertDatabaseHas('experiments', ['id' => $experiment->id, 'loading' => true]);

        // Now flip it to make sure it changes
        $this->json('post', '/api/etl/updateExperimentProgressStatus', [
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
            'loading'       => false,
        ])
             ->assertStatus(200)
             ->assertJsonFragment(['success' => true]);
        $this->assertDatabaseHas('experiments', ['id' => $experiment->id, 'loading' => false]);
    }
}
