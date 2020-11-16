<?php

namespace Tests\Feature\Http\Controllers\Api\Experiments;

use App\Models\Dataset;
use App\Models\Entity;
use App\Models\User;
use Carbon\Carbon;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteExperimentApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function project_owner_can_delete_experiment()
    {
        $this->withoutExceptionHandling();
        $member = User::factory()->create();
        $project = ProjectFactory::create();
        ProjectFactory::addMemberToProject($member, $project);
        $experiment = ProjectFactory::createExperimentInProject($project, $member->id);
        $this->actingAs($project->owner, 'api')
             ->json('delete', route('api.projects.experiments.delete', [$project, $experiment]))
             ->assertStatus(200);
        $this->assertDatabaseMissing('experiments', ['id' => $experiment->id, 'project_id' => $project->id]);
    }

    /** @test */
    public function experiment_owner_can_delete_experiment()
    {
        $this->withoutExceptionHandling();
        $member = User::factory()->create();
        $project = ProjectFactory::create();
        ProjectFactory::addMemberToProject($member, $project);
        $experiment = ProjectFactory::createExperimentInProject($project, $member->id);
        $this->actingAs($member, 'api')
             ->json('delete', route('api.projects.experiments.delete', [$project, $experiment]))
             ->assertStatus(200);
        $this->assertDatabaseMissing('experiments', ['id' => $experiment->id, 'project_id' => $project->id]);
    }

    /** @test */
    public function user_who_is_not_owner_of_experiment_or_project_cannot_delete_experiment()
    {
        $member = User::factory()->create();
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        ProjectFactory::addMemberToProject($member, $project);
        $this->actingAs($member, 'api')
             ->json('delete', route('api.projects.experiments.delete', [$project, $experiment]))
             ->assertStatus(403);
        $this->assertDatabaseHas('experiments', ['id' => $experiment->id, 'project_id' => $project->id]);
    }

    /** @test */
    public function experiment_cannot_be_deleted_when_it_overlaps_with_published_dataset()
    {
        // Create project and experiment
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();

        // Create dataset attached to project
        $dataset = Dataset::factory()->create([
            'owner_id'     => $project->owner_id,
            'published_at' => Carbon::now(),
        ]);

        // Create entity, attach to project, dataset, and experiment
        $entity = Entity::factory()->create([
            'owner_id'   => $project->owner_id,
            'project_id' => $project->id,
        ]);
        $dataset->entities()->attach($entity);
        $experiment->entities()->attach($entity);

        // Now that we are setup we have a published dataset that has an entity that is also
        // in the experiment we wish to delete. This should fail.
        $this->actingAs($project->owner, 'api')
             ->json('delete', route('api.projects.experiments.delete', [$project, $experiment]))
             ->assertStatus(403);
        $this->assertDatabaseHas('experiments', ['id' => $experiment->id, 'project_id' => $project->id]);
    }
}
