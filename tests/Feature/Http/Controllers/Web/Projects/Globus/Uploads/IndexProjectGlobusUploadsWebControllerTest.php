<?php

namespace Tests\Feature\Http\Controllers\Web\Projects\Globus\Uploads;

use App\Enums\GlobusStatus;
use App\Models\GlobusUploadDownload;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexProjectGlobusUploadsWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_show_upload_request_as_uploading()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'globus_user' => 'gtarcea@umich.edu',
        ]);
        $project = ProjectFactory::ownedBy($user)->create();

        $this->actingAs($user);
        $response = $this->post(route('projects.globus.uploads.store', [$project]), [
            'name' => 'test upload',
        ]);

        $response->assertStatus(302);

        $this->get(route('projects.globus.uploads.index', [$project]))
             ->assertSee('Open for Uploads/Uploading files');
    }

    /** @test */
    public function globus_upload_should_show_waiting_to_process_when_marked_done()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'globus_user' => 'gtarcea@umich.edu',
        ]);
        $project = ProjectFactory::ownedBy($user)->create();

        $this->actingAs($user);
        $this->post(route('projects.globus.uploads.store', [$project]), [
            'name' => 'test upload',
        ]);

        $this->post(route('projects.globus.uploads.mark_done', [$project->id, 1]))
             ->assertStatus(302);

        $this->get(route('projects.globus.uploads.index', [$project]))
             ->assertSee('Waiting to process files');
    }

    /** @test */
    public function globus_upload_should_show_processing_files_message_when_processing_starts()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'globus_user' => 'gtarcea@umich.edu',
        ]);
        $project = ProjectFactory::ownedBy($user)->create();

        $this->actingAs($user);
        $this->post(route('projects.globus.uploads.store', [$project]), [
            'name' => 'test upload',
        ]);

        $this->post(route('projects.globus.uploads.mark_done', [$project->id, 1]))
             ->assertStatus(302);

        // Get globus upload request and mark it as being processed
        $globusUpload = GlobusUploadDownload::find(1);
        $globusUpload->update(['status' => GlobusStatus::Loading]);

        $this->get(route('projects.globus.uploads.index', [$project]))
             ->assertSee('Processing files');
    }
}
