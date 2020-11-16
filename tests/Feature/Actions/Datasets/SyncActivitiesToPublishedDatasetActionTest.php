<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\SyncActivitiesToPublishedDatasetAction;
use App\Models\Activity;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncActivitiesToPublishedDatasetActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_sync_the_activites_in_entities_to_the_dataset()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $project = Project::factory()->create(['owner_id' => $user->id]);

        $entity = Entity::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $activity = Activity::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $activity->entities()->attach($entity);

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $dataset->entities()->attach($entity);

        $syncActivities = new SyncActivitiesToPublishedDatasetAction();
        $syncActivities->execute($dataset);

        $this->assertDatabaseHas('dataset2activity', ['dataset_id' => $dataset->id, 'activity_id' => $activity->id]);
        $this->assertEquals(1, $dataset->activities()->count());
    }
}
