<?php

namespace Tests\Feature\Actions\Activities;

use App\Actions\Activities\CreateActivityAction;
use App\Models\Activity;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateActivityActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_activity_in_project()
    {
        $project = ProjectFactory::create();
        $createAction = new CreateActivityAction();
        $activity = $createAction(['name' => 'activity1', 'project_id' => $project->id], $project->owner_id);
        $this->assertNotNull($activity);
        $this->assertDatabaseHas('activities', [
            'project_id' => $project->id,
            'id'         => $activity->id,
            'name'       => 'activity1',
            'owner_id'   => $project->owner_id,
        ]);
    }

    /** @test */
    public function created_activity_is_associated_with_experiment()
    {
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $createAction = new CreateActivityAction();
        $activity = $createAction([
            'name'          => 'activity1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
        ], $project->owner_id);

        $this->assertDatabaseHas('experiment2activity', [
            'experiment_id' => $experiment->id,
            'activity_id'   => $activity->id,
        ]);
    }

    /** @test */
    public function created_activity_has_given_attribute()
    {
        $project = ProjectFactory::create();
        $createAction = new CreateActivityAction();
        $activity = $createAction([
            'name'       => 'activity1',
            'project_id' => $project->id,
            'attributes' => [
                [
                    'name'   => 'attr1',
                    'unit'   => 'mm',
                    'value'  => 2,
                    'eindex' => 0,
                ],
                [
                    'name'  => 'attr2',
                    'value' => 'str',
                ],
            ],
        ], $project->owner_id);

        $this->assertDatabaseHas('attributes', [
            'name'              => 'attr1',
            'eindex'            => 0,
            'attributable_type' => Activity::class,
            'attributable_id'   => $activity->id,
        ]);

        $this->assertDatabaseHas('attributes', [
            'name'              => 'attr2',
            'eindex'            => null,
            'attributable_type' => Activity::class,
            'attributable_id'   => $activity->id,
        ]);

        $activity->load('attributes');
        $attr1 = $activity->attributes->firstWhere('name', 'attr1');
        $attr2 = $activity->attributes->firstWhere('name', 'attr2');
        $this->assertDatabaseHas('attribute_values', [
            'attribute_id' => $attr1->id,
            'unit'         => 'mm',
            'val'          => json_encode(['value' => 2]),
        ]);

        $this->assertDatabaseHas('attribute_values', [
            'attribute_id' => $attr2->id,
            'unit'         => '',
            'val'          => json_encode(['value' => 'str']),
        ]);
    }
}
