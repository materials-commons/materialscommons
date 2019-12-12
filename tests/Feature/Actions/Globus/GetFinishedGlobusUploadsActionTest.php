<?php

namespace Tests\Feature\Actions\Globus;

use App\Actions\Globus\GetFinishedGlobusUploadsAction;
use App\Models\GlobusUpload;
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
        factory(GlobusUpload::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
            'loading'    => false,
            'uploading'  => false,
        ]);
        factory(GlobusUpload::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
            'loading'    => true,
            'uploading'  => false,
        ]);

        $getFinishedGlobusUploadsAction = new GetFinishedGlobusUploadsAction();
        $finishedUploads = $getFinishedGlobusUploadsAction();
        $this->assertEquals(0, sizeof($finishedUploads));

        // Add an upload from a different project, and check that we get it back
        $project2 = factory(Project::class)->create(['owner_id' => $user->id]);
        $uploadToProcess = factory(GlobusUpload::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project2->id,
            'loading'    => false,
            'uploading'  => false,
        ]);

        $finishedUploads = $getFinishedGlobusUploadsAction();
        $this->assertEquals(1, sizeof($finishedUploads));
        $this->assertEquals($uploadToProcess->project_id, $finishedUploads[0]->project_id);
        $this->assertEquals($uploadToProcess->id, $finishedUploads[0]->id);
    }
}
