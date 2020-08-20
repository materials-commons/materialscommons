<?php

namespace Tests\Feature\Actions\Experiments;

use App\Actions\Experiments\DeleteExperimentAction;
use Facades\Tests\Factories\ExperimentFactory;
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
}
