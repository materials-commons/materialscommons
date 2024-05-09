<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\PublishDatasetAction2;
use App\Actions\Datasets\RefreshPublishedDatasetAction;
use App\Models\Activity;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RefreshPublishedDatasetActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_refreshes_a_published_dataset(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $project = Project::factory()->create(['owner_id' => $user->id]);

        // First publish a dataset.

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

        $publishDatasetAction2 = new PublishDatasetAction2();
        $publishDatasetAction2->execute($dataset, $user);

        $this->assertDatabaseHas('dataset2activity', ['dataset_id' => $dataset->id, 'activity_id' => $activity->id]);
        $this->assertEquals(1, $dataset->activities()->count());

        // Now republish it.
        $refreshPublishedDataset = new RefreshPublishedDatasetAction();
        $refreshPublishedDataset->execute($dataset, $user);

        // For now the test is that it doesn't throw an exception.

        Storage::disk('mcfs')->deleteDirectory("__datasets/{$dataset->uuid}");
    }
}
