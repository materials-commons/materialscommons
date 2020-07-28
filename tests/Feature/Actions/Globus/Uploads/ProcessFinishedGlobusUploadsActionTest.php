<?php

namespace Tests\Feature\Actions\Globus\Uploads;

use App\Actions\Globus\Uploads\ProcessFinishedGlobusUploadsAction;
use App\Enums\GlobusStatus;
use App\Enums\GlobusType;
use App\Jobs\Globus\ImportGlobusUploadJob;
use App\Models\GlobusUploadDownload;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessFinishedGlobusUploadsActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function there_should_be_no_queued_jobs_when_only_uploads_with_projects_being_processed_are_available()
    {
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        factory(GlobusUploadDownload::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
            'type'       => GlobusType::ProjectUpload,
            'status'     => GlobusStatus::Done,
        ]);
        factory(GlobusUploadDownload::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
            'type'       => GlobusType::ProjectUpload,
            'status'     => GlobusStatus::Loading,
        ]);

        Queue::fake();
        Queue::assertNothingPushed();

        $processFinishedGlobusUploadsAction = new ProcessFinishedGlobusUploadsAction();
        $processFinishedGlobusUploadsAction(true, false, false);
        Queue::assertNothingPushed();
    }

    /** @test */
    public function only_the_upload_for_project_not_being_processed_should_be_queued()
    {
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        factory(GlobusUploadDownload::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
            'type'       => GlobusType::ProjectUpload,
            'status'     => GlobusStatus::Done,
        ]);
        factory(GlobusUploadDownload::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
            'type'       => GlobusType::ProjectUpload,
            'status'     => GlobusStatus::Loading,
        ]);
        $project2 = factory(Project::class)->create(['owner_id' => $user->id]);
        $uploadToProcess = factory(GlobusUploadDownload::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project2->id,
            'type'       => GlobusType::ProjectUpload,
            'status'     => GlobusStatus::Done,
        ]);

        Queue::fake();
        Queue::assertNothingPushed();
        $processFinishedGlobusUploadsAction = new ProcessFinishedGlobusUploadsAction();
        $processFinishedGlobusUploadsAction(true, false, false);
        Queue::assertPushedOn('globus', ImportGlobusUploadJob::class);
        $this->assertEquals(1, Queue::size('globus'));
        Queue::assertPushed(ImportGlobusUploadJob::class, 1);
        Queue::assertPushed(ImportGlobusUploadJob::class, function (ImportGlobusUploadJob $job) use ($uploadToProcess) {
            return $job->globusUpload->id === $uploadToProcess->id;
        });
    }
}
