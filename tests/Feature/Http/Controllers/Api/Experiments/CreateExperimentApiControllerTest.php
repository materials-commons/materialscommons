<?php

namespace Tests\Feature\Http\Controllers\Api\Experiments;

use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateExperimentApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_experiment()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $this->actingAs($user, 'api');

        $this->json('post', '/api/experiments', [
            'name'       => 'e1',
            'project_id' => $project->id,
        ])
             ->assertStatus(201)
             ->assertJsonFragment(['name' => 'e1']);
    }
}
