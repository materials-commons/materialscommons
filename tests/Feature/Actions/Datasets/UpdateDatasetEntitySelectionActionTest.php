<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Actions\Entities\CreateEntityAction;
use App\Models\Dataset;
use Facades\Tests\Factories\DatasetFactory;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UpdateDatasetEntitySelectionActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_adds_an_entity_to_the_dataset_entity_selection()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $dataset = DatasetFactory::inProject($project)->create();
        $createAction = new CreateEntityAction();
        $entity = $createAction([
            'name'          => 'e1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
        ], $project->owner_id);

        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection->update($entity, $dataset);
        $this->assertDatabaseHas('item2entity_selection', [
            'entity_name'   => $entity->name,
            'experiment_id' => $experiment->id,
            'item_type'     => Dataset::class,
            'item_id'       => $dataset->id,
        ]);
    }

    /** @test */
    public function it_removes_an_existing_entity_selection()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $dataset = DatasetFactory::inProject($project)->create();
        $createAction = new CreateEntityAction();
        $entity = $createAction([
            'name'          => 'e1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
        ], $project->owner_id);

        // First add an entity and ensure it was properly added
        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection->update($entity, $dataset);
        $this->assertDatabaseHas('item2entity_selection', [
            'entity_name'   => $entity->name,
            'experiment_id' => $experiment->id,
            'item_type'     => Dataset::class,
            'item_id'       => $dataset->id,
        ]);

        // Now update again should remove it
        $updateSelection->update($entity, $dataset);
        $this->assertDatabaseMissing('item2entity_selection', [
            'entity_name'   => $entity->name,
            'experiment_id' => $experiment->id,
            'item_type'     => Dataset::class,
            'item_id'       => $dataset->id,
        ]);

        $this->assertEquals(0, DB::table('item2entity_selection')->count());
    }
}
