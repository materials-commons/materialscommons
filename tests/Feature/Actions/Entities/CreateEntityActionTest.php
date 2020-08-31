<?php

namespace Tests\Feature\Actions\Entities;

use App\Actions\Entities\CreateEntityAction;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateEntityActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function created_entity_has_an_entity_state()
    {
        $project = ProjectFactory::create();
        $createAction = new CreateEntityAction();
        $entity = $createAction([
            'name'       => 'e1',
            'project_id' => $project->id,
        ], $project->owner_id);

        $this->assertDatabaseHas('entities', ['id' => $entity->id]);
        $this->assertDatabaseHas('entity_states', ['entity_id' => $entity->id]);
    }

    /** @test */
    public function created_entity_is_associated_with_experiment()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $createAction = new CreateEntityAction();
        $entity = $createAction([
            'name'          => 'e1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
        ], $project->owner_id);

        $this->assertDatabaseHas('experiment2entity', ['experiment_id' => $experiment->id, 'entity_id' => $entity->id]);
    }
}
