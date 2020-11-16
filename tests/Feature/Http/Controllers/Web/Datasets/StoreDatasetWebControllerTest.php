<?php

namespace Tests\Feature\Http\Controllers\Web\Datasets;

use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreDatasetWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_dataset()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $this->actingAs($user)->post(route('projects.datasets.store', [$project]), [
            'name'       => 'ds1',
            'project_id' => $project->id,
            'action'     => "save",
        ])->assertStatus(302);

        $this->assertDatabaseHas('datasets', ['project_id' => $project->id, 'name' => 'ds1']);
    }
}
