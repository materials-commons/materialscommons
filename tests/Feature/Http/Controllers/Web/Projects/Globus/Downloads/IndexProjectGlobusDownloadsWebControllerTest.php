<?php

namespace Tests\Feature\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Enums\GlobusStatus;
use App\Jobs\Globus\CreateGlobusProjectDownloadDirsJob;
use App\Models\File;
use App\Models\GlobusUploadDownload;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class IndexProjectGlobusDownloadsWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function download_should_show_request_as_waiting_after_creation()
    {
        $this->withoutExceptionHandling();
        Bus::fake();

        $user = User::factory()->create([
            'globus_user' => 'gtarcea@umich.edu',
        ]);
        $project = ProjectFactory::ownedBy($user)->create();

        File::factory()->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        $this->actingAs($user);
        $this->post(route('projects.globus.downloads.store', [$project]), [
            'name' => 'test download',
        ])->assertStatus(302);

        Bus::assertDispatched(CreateGlobusProjectDownloadDirsJob::class);

        $this->get(route('projects.globus.downloads.index', [$project]))
             ->assertSee('Waiting to start creating project download')
             ->assertDontSee('Goto Globus');
//             ->assertDontSee('delete');
    }

    /** @test */
    public function download_should_show_creating_when_status_is_loading()
    {
        $this->withoutExceptionHandling();
        Bus::fake();

        $user = User::factory()->create([
            'globus_user' => 'gtarcea@umich.edu',
        ]);
        $project = ProjectFactory::ownedBy($user)->create();

        File::factory()->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        $this->actingAs($user);
        $this->post(route('projects.globus.downloads.store', [$project]), [
            'name' => 'test download',
        ])->assertStatus(302);

        Bus::assertDispatched(CreateGlobusProjectDownloadDirsJob::class);

        $globusDownload = GlobusUploadDownload::find(1);
        $globusDownload->update(['status' => GlobusStatus::Loading]);

        $response = $this->get(route('projects.globus.downloads.index', [$project]));
        $response->assertSee('Creating project download');
        $response->assertDontSee('Goto Globus');
    }

    /** @test */
    public function download_should_show_ready_to_use_and_globus_link_when_download_ready()
    {
        $this->withoutExceptionHandling();
        Bus::fake();

        $user = User::factory()->create([
            'globus_user' => 'gtarcea@umich.edu',
        ]);
        $project = ProjectFactory::ownedBy($user)->create();

        File::factory()->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        $this->actingAs($user);
        $this->post(route('projects.globus.downloads.store', [$project]), [
            'name' => 'test download',
        ])->assertStatus(302);

        Bus::assertDispatched(CreateGlobusProjectDownloadDirsJob::class);

        $globusDownload = GlobusUploadDownload::find(1);
        $globusDownload->update(['status' => GlobusStatus::Done]);

        $this->get(route('projects.globus.downloads.index', [$project]))
             ->assertSee('Ready to use')
             ->assertSee('Goto Globus')
             ->assertSee('delete');
    }
}
