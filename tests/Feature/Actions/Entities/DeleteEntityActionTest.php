<?php

namespace Tests\Feature\Actions\Entities;

use App\Actions\Entities\CreateEntityAction;
use App\Actions\Entities\DeleteEntityAction;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\EntityState;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteEntityActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function delete_removes_entity_state_attributes_and_values()
    {
        $project = ProjectFactory::create();
        $createAction = new CreateEntityAction();
        $entity = $createAction([
            'name'       => 'e1',
            'project_id' => $project->id,
        ], $project->owner_id);
        $es = $entity->entityStates->first();
        $a = Attribute::create([
            'name'              => 'a1',
            'attributable_id'   => $es->id,
            'attributable_type' => EntityState::class,
        ]);
        $av = AttributeValue::create([
            'attribute_id' => $a->id,
            'unit'         => '',
            'val'          => ['value' => 1],
        ]);

        $deleteAction = new DeleteEntityAction();
        $deleteAction($entity);

        $this->assertDatabaseMissing('entities', ['id' => $entity->id]);
        $this->assertDatabaseMissing('entity_states', ['id' => $es->id]);
        $this->assertDatabaseMissing('attributes', ['id' => $a->id]);
        $this->assertDatabaseMissing('attribute_values', ['id' => $av->id]);
    }
}
