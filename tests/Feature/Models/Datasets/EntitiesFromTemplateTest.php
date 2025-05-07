<?php

namespace Tests\Feature\Models\Datasets;

use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Models\Dataset;
use App\Models\Entity;
use Facades\Tests\Factories\DatasetFactory;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EntitiesFromTemplateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_retrieves_named_entities()
    {
        $project = ProjectFactory::withExperiment()->create();

        $dataset = DatasetFactory::inProject($project)->ownedBy($project->owner)->create();
        $experiment = $project->experiments->first();
        $e1 = Entity::factory()->create([
            'project_id' => $project->id,
        ]);
        $e2 = Entity::factory()->create([
            'project_id' => $project->id,
        ]);

        $experiment->entities()->attach($e1);
        $experiment->entities()->attach($e2);

        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection($e1, $dataset);
        $updateSelection($e2, $dataset);
        $entitiesInDataset = $dataset->entitiesFromTemplate();
        $this->assertCount(2, $entitiesInDataset);
    }

    /** @test */
    public function it_retrieves_entities_with_id()
    {
        $project = ProjectFactory::create();

        $dataset = DatasetFactory::inProject($project)->ownedBy($project->owner)->create();
        $e1 = Entity::factory()->create([
            'project_id' => $project->id,
        ]);
        $e2 = Entity::factory()->create([
            'project_id' => $project->id,
        ]);

        DB::table('item2entity_selection')->insert([
            'item_type' => Dataset::class,
            'item_id'   => $dataset->id,
            'entity_id' => $e1->id,
        ]);

        DB::table('item2entity_selection')->insert([
            'item_type' => Dataset::class,
            'item_id'   => $dataset->id,
            'entity_id' => $e2->id,
        ]);

        $entitiesInDataset = $dataset->entitiesFromTemplate();
        $this->assertCount(2, $entitiesInDataset);
    }

    /** @test */
    public function it_retrieves_entities_from_name_and_id()
    {
        $project = ProjectFactory::withExperiment()->create();

        $dataset = DatasetFactory::inProject($project)->ownedBy($project->owner)->create();
        $experiment = $project->experiments->first();
        $e1 = Entity::factory()->create([
            'project_id' => $project->id,
        ]);
        $e2 = Entity::factory()->create([
            'project_id' => $project->id,
        ]);

        $experiment->entities()->attach($e1);
        $experiment->entities()->attach($e2);

        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection->update($e1, $dataset);
        DB::table('item2entity_selection')->insert([
            'item_type' => Dataset::class,
            'item_id'   => $dataset->id,
            'entity_id' => $e2->id,
        ]);

        $entitiesInDataset = $dataset->entitiesFromTemplate();
        $this->assertCount(2, $entitiesInDataset);
    }
}
