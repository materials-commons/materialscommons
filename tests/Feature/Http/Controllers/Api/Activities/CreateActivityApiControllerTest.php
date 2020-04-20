<?php

namespace Tests\Feature\Http\Controllers\Api\Activities;

use App\Models\Activity;
use App\Models\Attribute;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateActivityApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_an_activity()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\Models\User')->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $experiment = factory('App\Models\Experiment')->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $this->actingAs($user, 'api');

        $activity = $this->json('post', '/api/activities', [
            'name'          => 'p1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
        ])
                         ->assertStatus(201)
                         ->assertJsonFragment(['name' => 'p1'])
                         ->decodeResponseJson();

        $activityId = $activity["data"]["id"];

        $this->assertDatabaseHas('experiment2activity',
            ['experiment_id' => $experiment->id, 'activity_id' => $activityId]);
        $this->assertDatabaseHas('activities', ['id' => $activityId, 'project_id' => $project->id]);
    }

    /** @test */
    public function it_should_create_an_activity_with_attributes()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\Models\User')->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $experiment = factory('App\Models\Experiment')->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $this->actingAs($user, 'api');

        $activity = $this->json('post', '/api/activities', [
            'name'          => 'p1',
            'project_id'    => $project->id,
            'experiment_id' => $experiment->id,
            'attributes'    => [['name' => 'attr1', 'unit' => 'm', 'value' => 4]],
        ])
                         ->assertStatus(201)
                         ->assertJsonFragment(['name' => 'p1'])
                         ->decodeResponseJson();

        $activityId = $activity["data"]["id"];

        $this->assertDatabaseHas('experiment2activity',
            ['experiment_id' => $experiment->id, 'activity_id' => $activityId]);
        $this->assertDatabaseHas('activities', ['id' => $activityId, 'project_id' => $project->id]);

        // Ensure that the attribute and its value were inserted properly
        $this->assertDatabaseHas('attributes',
            ['name' => 'attr1', 'attributable_id' => $activityId, 'attributable_type' => Activity::class]);
        $attr = Attribute::where('name', 'attr1')->first();
        $this->assertDatabaseHas('attribute_values', ['unit' => 'm', 'id' => $attr->id, 'val' => '{"value":4}']);
    }
}
