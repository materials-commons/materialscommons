<?php

namespace Tests\Feature\Models;

use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Models\Entity;
use Facades\Tests\Factories\DatasetFactory;
use Facades\Tests\Factories\ExperimentFactory;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function activityNamesForEntities_gets_all_unique_activity_names_for_entities()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments()->first();
        $entity1 = ExperimentFactory::createEntityForExperiment($experiment);
        $activityForEntity1 = ExperimentFactory::createActivityForExperiment($experiment);
        $entity1->activities()->attach($activityForEntity1);

        $entity2 = ExperimentFactory::createEntityForExperiment($experiment);
        $activityForEntity2 = ExperimentFactory::createActivityForExperiment($experiment);
        $activity2ForEntity2 = ExperimentFactory::createActivityForExperiment($experiment);
        $entity2->activities()->attach($activityForEntity2);
        $entity2->activities()->attach($activity2ForEntity2);

        $dataset = DatasetFactory::inProject($project)->create();

        // Add entities (as template) to dataset
        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection($entity1, $dataset);
        $updateSelection($entity2, $dataset);

        // Now perform queries to get the activities for the entities when using template query
        $entities = $dataset->entitiesFromTemplate();
        $activities = Entity::activityNamesForEntities($entities);
        $this->assertEquals(3, $activities->count());

        // Check that each of the activities names are in the activities collection
        $this->assertEquals(1, $activities->where('name', $activityForEntity1->name)->count());
        $this->assertEquals(1, $activities->where('name', $activityForEntity2->name)->count());
        $this->assertEquals(1, $activities->where('name', $activity2ForEntity2->name)->count());
    }
}
