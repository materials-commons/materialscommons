<?php

namespace Tests\Feature\Http\Controllers\Api\Entities;

use App\Actions\Activities\CreateActivityAction;
use App\Models\Experiment;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateEntityApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_an_entity()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $experiment = Experiment::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $createActivityAction = new CreateActivityAction();

        $activity = $createActivityAction([
            'experiment' => $experiment->id,
            'name'       => 'activity1',
            'project_id' => $project->id,
        ], $user->id);

        $this->actingAs($user, 'api');

        $entity = $this->json('post', '/api/entities', [
            'name'          => 's1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
            'activity_id'   => $activity->id,
            'category' => 'experimental',
        ])
                       ->assertStatus(201)
                       ->assertJsonFragment(['name' => 's1'])
                       ->decodeResponseJson();

        $entityId = $entity["data"]["id"];

        $this->assertDatabaseHas('experiment2entity', ['experiment_id' => $experiment->id, 'entity_id' => $entityId]);
        $this->assertDatabaseHas('entities', ['id' => $entityId, 'project_id' => $project->id]);
        $this->assertDatabaseHas('entity_states', ['entity_id' => $entityId]);
    }
}
