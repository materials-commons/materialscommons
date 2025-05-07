<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\ReplicateDatasetEntitiesAndRelationshipsForPublishingAction;
use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Actions\Entities\CreateEntityAction;
use App\Actions\Experiments\DeleteExperimentAction;
use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Entity;
use App\Models\EntityState;
use Facades\Tests\Factories\DatasetFactory;
use Facades\Tests\Factories\ExperimentFactory;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReplicateDatasetEntitiesAndRelationshipsForPublishingActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_replicates_entities()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $dataset = DatasetFactory::inProject($project)->create();
        $createEntity = new CreateEntityAction();
        $entity = $createEntity([
            'name'          => 'e1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
        ], $project->owner_id);

        // Add entity template to dataset
        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection->update($entity, $dataset);

        $this->assertEquals(1, Entity::count());

        // Now replicate
        $replicateAction = new ReplicateDatasetEntitiesAndRelationshipsForPublishingAction();
        $replicateAction->execute($dataset);

        // After replication we should have the original and the replicated entity (so 2)
        $this->assertEquals(2, Entity::count());

        // The replicated entity should have its copied_id set to the original entity
        $this->assertDatabaseHas('entities', ['copied_id' => $entity->id]);

        // Replication adds the replicated entity to the dataset2entity table. Before this the entities are just
        // templates associated with the dataset.
        $this->assertEquals(1, DB::table('dataset2entity')->count());

        // Replicated entities should have their copied_at attribute set to non-null
        $this->assertEquals(1, DB::table('entities')->whereNotNull('copied_at')->count());
    }

    /** @test */
    public function it_replicates_entities_states_and_attributes_and_values_for_states()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $dataset = DatasetFactory::inProject($project)->create();

        // Creates an entity with a state, attribute, and attribute value
        $entity = ExperimentFactory::createEntityForExperiment($experiment);

        // Add entity template to dataset
        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection->update($entity, $dataset);

        $this->assertEquals(1, Entity::count());
        $this->assertEquals(1, EntityState::count());
        $this->assertEquals(1, Attribute::count());
        $this->assertEquals(1, AttributeValue::count());

        // Replicate
        $replicateAction = new ReplicateDatasetEntitiesAndRelationshipsForPublishingAction();
        $replicateAction->execute($dataset);

        // After replication there should be 2 entities, 2 states, 2 attributes and 2
        // attribute values
        $this->assertEquals(2, Entity::count());
        $this->assertEquals(2, EntityState::count());
        $this->assertEquals(2, Attribute::count());
        $this->assertEquals(2, AttributeValue::count());

        // Replicated entity should have its own state, attribute and attribute value.
        // There should be one of each so we work our way through the different
        // relationships and check the counts.
        $replicatedEntity = Entity::where('copied_id', $entity->id)->first();
        $this->assertEquals(1, $replicatedEntity->entityStates()->count());
        $replicatedEntityState = $replicatedEntity->entityStates()->first();
        $this->assertEquals(1, $replicatedEntityState->attributes()->count());
        $replicatedAttribute = $replicatedEntityState->attributes()->first();
        $this->assertEquals(1, $replicatedAttribute->values()->count());
    }

    /** @test */
    public function it_replicates_activities_their_attributes_and_values_and_associates_replicates_entity_association()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $dataset = DatasetFactory::inProject($project)->create();

        // Creates an entity with a state, attribute, and attribute value
        $entity = ExperimentFactory::createEntityForExperiment($experiment);

        // Creates an activity, attribute and attribute value
        $activity = ExperimentFactory::createActivityForExperiment($experiment);
        $activity->entities()->attach($entity);

        // Add entity template to dataset
        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection->update($entity, $dataset);

        // Replicate
        $replicateAction = new ReplicateDatasetEntitiesAndRelationshipsForPublishingAction();
        $replicateAction->execute($dataset);

        // All we are going to test is that the activity, its attribute and the attribute value are replicated.
        // And that the replicated activity and replicated entity are associated with each other.
        // Other tests are taking care of checking the state of the entity, its state, attributes and values.

        // Make sure the activity was replicated
        $this->assertEquals(1, Activity::where('copied_id', $activity->id)->count());

        // Get the replicated activity
        $replicatedActivity = Activity::where('copied_id', $activity->id)->first();

        // The replicated activity should have a single attribute and attribute value associated with it
        $this->assertEquals(1, $replicatedActivity->attributes()->count());
        $replicatedAttribute = $replicatedActivity->attributes()->first();
        $this->assertEquals(1, $replicatedAttribute->values()->count());

        // The replicated activity should have a single entity associated with it, and that entity should
        // be the replicated entity
        $this->assertEquals(1, $replicatedActivity->entities()->count());
        $entityAssociatdWithReplicatedActivity = $replicatedActivity->entities()->first();
        $this->assertEquals($entityAssociatdWithReplicatedActivity->copied_id, $entity->id);
    }

    /** @test */
    public function it_replicates_activity_and_entity_state()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $dataset = DatasetFactory::inProject($project)->create();

        // Creates an entity with a state, attribute, and attribute value
        $entity = ExperimentFactory::createEntityForExperiment($experiment);

        // Creates an activity, attribute and attribute value
        $activity = ExperimentFactory::createActivityForExperiment($experiment);
        $activity->entities()->attach($entity);

        // Attach the entity state to the activity
        $entityState = $entity->entityStates()->first();
        $activity->entityStates()->attach($entityState);

        // Add entity template to dataset
        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection->update($entity, $dataset);

        // Replicate
        $replicateAction = new ReplicateDatasetEntitiesAndRelationshipsForPublishingAction();
        $replicateAction->execute($dataset);

        // Get the replicated activity
        $replicatedActivity = Activity::where('copied_id', $activity->id)->first();

        // The replicated activity should have one entity state associated with
        $replicatedActivityEntityStatesCount = $replicatedActivity->entityStates()->count();
        $this->assertEquals(1, $replicatedActivityEntityStatesCount);
    }

    /** @test */
    public function replicated_entities_and_activities_are_not_deleted_when_experiment_is_deleted()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $dataset = DatasetFactory::inProject($project)->create();

        // Creates an entity with a state, attribute, and attribute value
        $entity = ExperimentFactory::createEntityForExperiment($experiment);

        // Creates an activity, attribute and attribute value
        $activity = ExperimentFactory::createActivityForExperiment($experiment);
        $activity->entities()->attach($entity);

        // Add entity template to dataset
        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection->update($entity, $dataset);

        // Replicate
        $replicateAction = new ReplicateDatasetEntitiesAndRelationshipsForPublishingAction();
        $replicateAction->execute($dataset);

        // Make sure there are 2
        $this->assertEquals(2, Entity::count());
        $this->assertEquals(2, Activity::count());

        // Delete the experiment should result in replicated entity and activity remaining, while the original
        // entity and activity are deleted
        $deleteExperiment = new DeleteExperimentAction();
        $deleteExperiment($experiment);

        // Now after experiment delete there should only be the replicated entity and activity
        $this->assertEquals(1, Entity::count());
        $this->assertEquals(1, Activity::count());

        // Lets make sure it is the replicated items
        $this->assertEquals(1, Entity::whereNotNull('copied_at')->count());
        $this->assertEquals(1, Activity::whereNotNull('copied_at')->count());
    }
}
