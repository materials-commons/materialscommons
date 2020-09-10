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

        $user = factory(User::class)->create();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $entity = factory(Entity::class)->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $activity = factory(Activity::class)->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $activity->entities()->attach($entity);

        $dataset = factory(Dataset::class)->create([
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
