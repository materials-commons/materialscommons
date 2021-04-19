<?php

namespace Tests\Feature\Jobs;

use App\Actions\Datasets\PublishDatasetAction2;
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

        $job = new PublishDatasetAction2();
        $job->execute($dataset);

        $this->assertDatabaseHas('dataset2activity', ['dataset_id' => $dataset->id, 'activity_id' => $activity->id]);
        $this->assertEquals(1, $dataset->activities()->count());

        Storage::disk('mcfs')->deleteDirectory("__datasets/{$dataset->uuid}");
    }
}
