<?php

namespace Tests\Feature\Http\Controllers\Api\Entities;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateEntityApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_an_entity()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\Models\User')->create();
        $project = factory('App\Models\Project')->create([
            'owner_id' => $user->id,
        ]);
        $experiment = factory('App\Models\Experiment')->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $this->actingAs($user, 'api');

        $entity = $this->json('post', '/api/entities', [
            'name'          => 's1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
        ])
                       ->assertStatus(201)
                       ->assertJsonFragment(['name' => 's1'])
                       ->decodeResponseJson();

        $entityId = $entity["data"]["id"];

        $this->assertDatabaseHas('experiment2entity', ['experiment_id' => $experiment->id, 'entity_id' => $entityId]);
        $this->assertDatabaseHas('entities', ['id' => $entityId, 'project_id' => $project->id]);
    }
}
