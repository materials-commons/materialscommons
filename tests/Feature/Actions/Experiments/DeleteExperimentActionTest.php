<?php

namespace Tests\Feature\Actions\Experiments;

use App\Actions\Datasets\PublishDatasetAction2;
use App\Actions\Datasets\UpdateDatasetEntitySelectionAction;
use App\Actions\Experiments\DeleteExperimentAction;
use App\Models\Dataset;
use App\Models\Entity;
use Facades\Tests\Factories\ExperimentFactory;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteExperimentActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function deleting_an_experiment_deletes_its_entities_and_their_dependents()
    {
        $experiment = ExperimentFactory::withEntity()->create();
        $experiment->load('entities.entityStates.attributes.values');
        $entity = $experiment->entities->first();
        $es = $entity->entityStates()->first();
        $attr = $es->attributes()->first();
        $value = $attr->values()->first();

        $deleteExperiment = new DeleteExperimentAction();
        $deleteExperiment($experiment);
        $this->assertDatabaseMissing('experiments', ['id' => $experiment->id, 'project_id' => $experiment->project_id]);
        $this->assertDatabaseMissing('entities', ['id' => $entity->id]);
        $this->assertDatabaseMissing('entity_states', ['id' => $es->id]);
        $this->assertDatabaseMissing('attributes', ['id' => $attr->id]);
        $this->assertDatabaseMissing('attribute_values', ['id' => $value->id]);
    }

    /** @test */
    public function deleting_an_experiment_deletes_its_activities_and_their_dependents()
    {
        $experiment = ExperimentFactory::withActivity()->create();
        $experiment->load('activities.attributes.values');
        $activity = $experiment->activities()->first();
        $attr = $activity->attributes()->first();
        $value = $attr->values()->first();
        $deleteExperiment = new DeleteExperimentAction();
        $deleteExperiment($experiment);
        $this->assertDatabaseMissing('experiments', ['id' => $experiment->id, 'project_id' => $experiment->project_id]);
        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
        $this->assertDatabaseMissing('attributes', ['id' => $attr->id]);
        $this->assertDatabaseMissing('attribute_values', ['id' => $value->id]);
    }

    /** @test */
    public function deleting_an_experiment_does_not_affect_published_datasets_samples()
    {
        // Create project and experiment
        $project = ProjectFactory::withExperiment()->create();
        $project->load('owner');
        $experiment = $project->experiments->first();

        // Create dataset attached to project
        $dataset = Dataset::factory()->create([
            'owner_id'     => $project->owner_id,
            'published_at' => null,
        ]);

        // Create entity, attach to project, dataset, and experiment
        $entity = Entity::factory()->create([
            'owner_id'   => $project->owner_id,
            'project_id' => $project->id,
        ]);
        $experiment->entities()->attach($entity);

        // Add entity template to dataset
        $updateSelection = new UpdateDatasetEntitySelectionAction();
        $updateSelection->update($entity, $dataset);

        // Call the publish dataset action, then run delete and check that the dataset still has samples

        $publishAction = new PublishDatasetAction2();
        $publishAction->execute($dataset, $project->owner);

        $deleteExperiment = new DeleteExperimentAction();
        $deleteExperiment($experiment);
        $dataset->refresh();
        $this->assertEquals(1, $dataset->entities()->count());
    }
}
