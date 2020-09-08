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
        $project = ProjectFactory::ownedBy($user)->withExperiment()->create();
        $experiment = $project->experiments()->first();
        $dataset = factory(Dataset::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);
        $entity = factory(Entity::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);
        $experiment->entities()->attach($entity);

        $this->actingAs($user, 'api');

        $this->json('put', "/api/datasets/{$dataset->id}/entities", [
            'project_id' => $project->id,
            'entity_id'  => $entity->id,
        ])
             ->assertStatus(200);

        // Make sure entity is added to dataset
        $this->assertDatabaseHas('item2entity_selection', [
            'item_id'       => $dataset->id,
            'item_type'     => Dataset::class,
            'entity_name'   => $entity->name,
            'experiment_id' => $experiment->id,
        ]);

        // Issue the same call, which should remove the entity from the dataset
        $this->json('put', "/api/datasets/{$dataset->id}/entities", [
            'project_id' => $project->id,
            'entity_id'  => $entity->id,
        ])
             ->assertStatus(200);

        $this->assertDatabaseMissing('dataset2entity', [
            'item_id'       => $dataset->id,
            'item_type'     => Dataset::class,
            'entity_name'   => $entity->name,
            'experiment_id' => $experiment->id,
        ]);
    }
}
