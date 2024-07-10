<?php

namespace Tests\Feature\Actions\Datasets;

use App\Actions\Datasets\GetAndSavePublishedDatasetCitationsAction;
use App\Actions\Datasets\PublishDatasetAction2;
use App\Models\Activity;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Paper;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Utils\StorageUtils;

class GetAndSavePublishedDatasetCitationsActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dataset_with_paper_should_create_citations_file_for_paper(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $project = Project::factory()->create(['owner_id' => $user->id]);

        $dataset = $this->createPublishedDatasetWithPaper($user, $project);

        $getAndSaveDSCitations = new GetAndSavePublishedDatasetCitationsAction();
        $getAndSaveDSCitations->execute($dataset);
        $path = Storage::disk('mcfs')->path("__published_datasets/{$dataset->uuid}/citations.json");
//        echo "file path = {$path}\n";
        $this->assertFileExists($path);
        StorageUtils::clearStorage();
    }

    private function createPublishedDatasetWithPaper(User $user, Project $project)
    {
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

        $paper = Paper::factory()->create([
            'name'      => 'A paper',
            'doi'       => '10.1007/s11661-022-06702-5',
            'owner_id'  => $user->id,
            'reference' => '',
        ]);

        $dataset->papers()->attach($paper);

        $dataset->entities()->attach($entity);

        $job = new PublishDatasetAction2();
        $job->execute($dataset, $user);

        $dataset->refresh();

        return $dataset;
    }
}
