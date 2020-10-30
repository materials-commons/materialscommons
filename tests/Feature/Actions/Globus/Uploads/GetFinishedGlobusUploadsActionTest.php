<?php

namespace Tests\Feature\Actions\Globus\Uploads;

use App\Actions\Globus\Uploads\GetFinishedGlobusUploadsAction;
use App\Enums\GlobusStatus;
use App\Enums\GlobusType;
use App\Models\GlobusUploadDownload;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetFinishedGlobusUploadsActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_upload_for_a_project_that_has_a_upload_being_processed_should_not_be_shown()
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

        $getFinishedGlobusUploadsAction = new GetFinishedGlobusUploadsAction();
        $finishedUploads = $getFinishedGlobusUploadsAction();
        $this->assertEquals(0, sizeof($finishedUploads));

        // Add an upload from a different project, and check that we get it back
        $project2 = factory(Project::class)->create(['owner_id' => $user->id]);
        $uploadToProcess = factory(GlobusUploadDownload::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project2->id,
            'type'       => GlobusType::ProjectUpload,
            'status'     => GlobusStatus::Done,
        ]);

        $finishedUploads = $getFinishedGlobusUploadsAction();
        $this->assertEquals(1, sizeof($finishedUploads));
        $this->assertEquals($uploadToProcess->project_id, $finishedUploads[0]->project_id);
        $this->assertEquals($uploadToProcess->id, $finishedUploads[0]->id);
    }

    /** @test */
    public function upload_errors_are_handled_correctly_for_selection()
    {
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        // check null case on errors
        $gld = factory(GlobusUploadDownload::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
            'type'       => GlobusType::ProjectUpload,
            'status'     => GlobusStatus::Done,
        ]);
        $getFinishedGlobusUploadsAction = new GetFinishedGlobusUploadsAction();
        $finishedUploads = $getFinishedGlobusUploadsAction();
        $this->assertEquals(1, $finishedUploads->count());

        // check where it has errors but less than the amount we would ignore it on
        $gld->update(['errors' => 2]);
        $finishedUploads = $getFinishedGlobusUploadsAction();
        $this->assertEquals(1, $finishedUploads->count());

        // Check that it ignores when errors are set to 10
        $gld->update(['errors' => 10]);
        $finishedUploads = $getFinishedGlobusUploadsAction();
        $this->assertEquals(0, $finishedUploads->count());

        // Check that we get back an upload when a second one is added without errors
        $gld2 = factory(GlobusUploadDownload::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
            'type'       => GlobusType::ProjectUpload,
            'status'     => GlobusStatus::Done,
        ]);
        $finishedUploads = $getFinishedGlobusUploadsAction();
        $this->assertEquals(1, $finishedUploads->count());
    }
}
