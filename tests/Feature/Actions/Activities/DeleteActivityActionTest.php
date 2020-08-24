<?php

namespace Tests\Feature\Actions\Activities;

use App\Actions\Activities\CreateActivityAction;
use App\Actions\Activities\DeleteActivityAction;
use App\Models\AttributeValue;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteActivityActionTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function delete_removes_activity_attributes_and_values()
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
            ],
        ], $project->owner_id);

        $deleteAction = new DeleteActivityAction();
        $activity->load('attributes.values');
        $attr1 = $activity->attributes->firstWhere('name', 'attr1');

        $deleteAction($activity);

        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
        $this->assertDatabaseMissing('attributes', ['id' => $attr1->id]);
        $this->assertDatabaseMissing('attribute_values', ['attribute_id' => $attr1->id]);
        $this->assertEquals(0, AttributeValue::count());
    }
}
