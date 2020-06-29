<?php

namespace Tests\Feature\Jobs;

use App\Jobs\Datasets\PublishDatasetJob;
use App\Models\Activity;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublishDatasetJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_complete_publishing_dataset()
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

        $job = new PublishDatasetJob($dataset->id);
        $job->handle();

        $this->assertDatabaseHas('dataset2activity', ['dataset_id' => $dataset->id, 'activity_id' => $activity->id]);
        $this->assertEquals(1, $dataset->activities()->count());

        Storage::disk('mcfs')->deleteDirectory("__datasets/{$dataset->uuid}");
    }
}
