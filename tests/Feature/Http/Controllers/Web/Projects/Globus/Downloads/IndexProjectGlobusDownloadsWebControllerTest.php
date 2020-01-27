<?php

namespace Tests\Feature\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Enums\GlobusStatus;
use App\Models\GlobusUploadDownload;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexProjectGlobusDownloadsWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function download_should_show_request_as_waiting_after_creation()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'globus_user' => 'gtarcea@umich.edu',
        ]);
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $user->projects()->attach($project);
        $this->actingAs($user);
        $this->post(route('projects.globus.downloads.store', [$project]), [
            'name' => 'test download',
        ])->assertStatus(302);

        $this->get(route('projects.globus.downloads.index', [$project]))
             ->assertSee('Waiting to start creating project download')
             ->assertDontSee('Goto Globus');
    }

    /** @test */
    public function download_should_show_creating_when_status_is_loading()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'globus_user' => 'gtarcea@umich.edu',
        ]);
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $user->projects()->attach($project);
        $this->actingAs($user);
        $this->post(route('projects.globus.downloads.store', [$project]), [
            'name' => 'test download',
        ])->assertStatus(302);

        $globusDownload = GlobusUploadDownload::find(1);
        $globusDownload->update(['status' => GlobusStatus::Loading]);

        $this->get(route('projects.globus.downloads.index', [$project]))
             ->assertSee('Creating project download')
             ->assertDontSee('Goto Globus');
    }

    /** @test */
    public function download_should_show_ready_to_use_and_globus_link_when_download_ready()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'globus_user' => 'gtarcea@umich.edu',
        ]);
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $user->projects()->attach($project);
        $this->actingAs($user);
        $this->post(route('projects.globus.downloads.store', [$project]), [
            'name' => 'test download',
        ])->assertStatus(302);

        $globusDownload = GlobusUploadDownload::find(1);
        $globusDownload->update(['status' => GlobusStatus::Done]);

        $this->get(route('projects.globus.downloads.index', [$project]))
             ->assertSee('Ready to use')
             ->assertSee('Goto Globus');
    }
}
