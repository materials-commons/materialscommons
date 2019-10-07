<?php

namespace Tests\Feature\Http\Controllers\Web\Datasets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreDatasetWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_dataset()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\Models\User')->create();
        $project = factory('App\Models\Project')->create([
            'owner_id' => $user->id,
        ]);

        $this->actingAs($user, 'web');

        $this->post(route('projects.datasets.store', [$project]), [
            'name'       => 'ds1',
            'project_id' => $project->id,
        ])->assertStatus(302);

        $this->assertDatabaseHas('datasets', ['project_id' => $project->id]);
    }
}
