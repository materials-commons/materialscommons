<?php

namespace Tests\Feature\Http\Controllers\Api\Datasets;

use App\Models\Dataset;
use App\Models\Entity;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateDatasetEntitySelectionApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_and_remove_entities_from_dataset()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\Models\User')->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $dataset = factory(Dataset::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);
        $entity = factory(Entity::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $this->actingAs($user, 'api');

        $this->json('put', "/api/datasets/{$dataset->id}/entities", [
            'project_id' => $project->id,
            'entity_id'  => $entity->id,
        ])
             ->assertStatus(200);

        // Make sure entity is added to dataset
        $this->assertDatabaseHas('dataset2entity', ['dataset_id' => $dataset->id, 'entity_id' => $entity->id]);

        // Issue the same call, which should remove the entity from the dataset
        $this->json('put', "/api/datasets/{$dataset->id}/entities", [
            'project_id' => $project->id,
            'entity_id'  => $entity->id,
        ])
             ->assertStatus(200);

        $this->assertDatabaseMissing('dataset2entity', ['dataset_id' => $dataset->id, 'entity_id' => $entity->id]);
    }
}
