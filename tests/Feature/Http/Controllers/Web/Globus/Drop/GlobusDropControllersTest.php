<?php

namespace Tests\Feature\Http\Controllers\Web\Globus\Drop;

use App\Actions\Globus\GlobusApi;
use App\Jobs\Globus\ImportGlobusDropFilesJob;
use App\Livewire\Projects\GlobusDrops\CreateDrop;
use App\Livewire\Projects\GlobusDrops\ImportDrop;
use App\Mail\Globus\DropAvailableForUseMail;
use App\Mail\Globus\DropReadyForImportMail;
use App\Models\File;
use App\Models\GlobusDrop;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class GlobusDropControllersTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_index_displays_globus_drops_for_project(): void
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $drop = GlobusDrop::factory()->create([
            'project_id'      => $project->id,
            'owner_id'        => $user->id,
            'name'            => 'Experiment Drop',
            'status'          => 'available',
            'globus_accounts' => ['researcher@example.com'],
        ]);

        GlobusDrop::factory()->create();

        $this->actingAs($user)
             ->get(route('projects.globus.drops.index', [$project]))
             ->assertOk()
             ->assertViewIs('app.projects.globus.drops.index')
             ->assertViewHas('project', $project)
             ->assertSee('Experiment Drop')
             ->assertSee('available')
             ->assertSee('researcher@example.com')
             ->assertSee(route('projects.globus.drops.import', [$project, $drop]), false)
             ->assertSee(route('projects.globus.drops.delete', [$project, $drop]), false);
    }

    public function test_create_page_loads_create_drop_livewire_ui(): void
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $this->actingAs($user)
             ->get(route('projects.globus.drops.create', [$project]))
             ->assertOk()
             ->assertViewIs('app.projects.globus.drops.create')
             ->assertSee('Create Drop')
             ->assertSeeLivewire(CreateDrop::class);
    }

    public function test_create_drop_livewire_component_adds_and_removes_globus_users(): void
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $this->mockGlobusApi();

        Livewire::actingAs($user)
                ->test(CreateDrop::class, ['project' => $project])
                ->set('globusUsers.0', 'researcher@example.com')
                ->call('addGlobusUser')
                ->assertSet('globusUsers.0', 'researcher@example.com')
                ->assertSet('globusUsers.1', '')
                ->call('removeGlobusUser', 0)
                ->assertSet('globusUsers.0', '');
    }

    public function test_create_drop_livewire_component_validates_required_ui_fields(): void
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $this->mockGlobusApi();

        Livewire::actingAs($user)
                ->test(CreateDrop::class, ['project' => $project])
                ->set('name', '')
                ->set('globusUsers', ['not-an-email'])
                ->call('save')
                ->assertHasErrors([
                    'name'          => 'required',
                    'globusUsers.0' => 'email',
                ]);
    }

    public function test_create_drop_livewire_component_creates_drop_and_redirects(): void
    {
        Mail::fake();
        Storage::fake('mcfs');

        config(['globus.endpoint' => 'test-endpoint-id']);

        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $this->mockGlobusApi();

        Livewire::actingAs($user)
                ->test(CreateDrop::class, ['project' => $project])
                ->set('name', 'New Experiment Drop')
                ->set('message', 'Please upload your files.')
                ->set('globusUsers', ['researcher@example.com'])
                ->call('save')
                ->assertHasNoErrors()
                ->assertRedirect(route('projects.globus.drops.index', [$project]));

        $drop = GlobusDrop::where('name', 'New Experiment Drop')->first();

        $this->assertNotNull($drop);
        $this->assertSame($project->id, $drop->project_id);
        $this->assertSame($user->id, $drop->owner_id);
        $this->assertSame('available', $drop->status);
        $this->assertSame(['researcher@example.com'], $drop->globus_accounts);
        $this->assertSame('test-endpoint-id', $drop->globus_endpoint_id);
        $this->assertSame("/__globus_uploads/drops/{$drop->id}/New Experiment Drop/", $drop->globus_path);

        Mail::assertQueued(DropAvailableForUseMail::class);
    }

    public function test_import_page_loads_import_drop_livewire_ui(): void
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $drop = GlobusDrop::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $this->actingAs($user)
             ->get(route('projects.globus.drops.import', [$project, $drop]))
             ->assertOk()
             ->assertViewIs('app.projects.globus.drops.import')
             ->assertSee('Import Drop')
             ->assertSeeLivewire(ImportDrop::class);
    }

    public function test_import_page_returns_not_found_when_drop_is_for_different_project(): void
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $otherProject = Project::factory()->create(['owner_id' => $user->id]);

        $drop = GlobusDrop::factory()->create([
            'project_id' => $otherProject->id,
            'owner_id'   => $user->id,
        ]);

        $this->actingAs($user)
             ->get(route('projects.globus.drops.import', [$project, $drop]))
             ->assertNotFound();
    }

    public function test_import_drop_livewire_component_validates_existing_destination_directory(): void
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $drop = GlobusDrop::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $this->mockGlobusApi();

        Livewire::actingAs($user)
                ->test(ImportDrop::class, [
                    'project' => $project,
                    'drop'    => $drop,
                ])
                ->set('destinationMode', 'existing')
                ->set('directoryId', 999999)
                ->call('import')
                ->assertHasErrors(['directoryId']);
    }

    public function test_import_drop_livewire_component_validates_new_destination_directory_path(): void
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $drop = GlobusDrop::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $this->mockGlobusApi();

        Livewire::actingAs($user)
                ->test(ImportDrop::class, [
                    'project' => $project,
                    'drop'    => $drop,
                ])
                ->set('destinationMode', 'new')
                ->set('directoryPath', 'invalid/path')
                ->call('import')
                ->assertHasErrors(['directoryPath']);
    }

    public function test_import_drop_livewire_component_shows_error_when_drop_directory_does_not_exist(): void
    {
        Storage::fake('mcfs');

        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $rootDirectory = $project->rootDir;

//        $rootDirectory = $this->createProjectDirectory($project, '/');

        $drop = GlobusDrop::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'name'       => 'Missing Drop Directory',
        ]);

        $this->mockGlobusApi();

        Livewire::actingAs($user)
                ->test(ImportDrop::class, [
                    'project' => $project,
                    'drop'    => $drop,
                ])
                ->set('destinationMode', 'existing')
                ->set('directoryId', $rootDirectory->id)
                ->call('import')
                ->assertHasErrors(['drop']);
    }

    public function test_import_drop_livewire_component_starts_import_and_dispatches_import_job(): void
    {
        Bus::fake();
        Storage::fake('mcfs');

        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $rootDirectory = $project->rootDir;

        $drop = GlobusDrop::factory()->create([
            'project_id'         => $project->id,
            'owner_id'           => $user->id,
            'name'               => 'Ready Drop',
            'status'             => 'ready for import',
            'globus_endpoint_id' => 'test-endpoint-id',
            'globus_path'        => '/__globus_uploads/drops/1/Ready Drop/',
        ]);

        Storage::disk('mcfs')->makeDirectory("__globus_uploads/drops/{$drop->id}/{$drop->name}");
        Storage::disk('mcfs')->put("__globus_uploads/drops/{$drop->id}/{$drop->name}/file.txt", 'file contents');

        $this->mockGlobusApi();

        Livewire::actingAs($user)
                ->test(ImportDrop::class, [
                    'project' => $project,
                    'drop'    => $drop,
                ])
                ->set('destinationMode', 'existing')
                ->set('directoryId', $rootDirectory->id)
                ->call('import')
                ->assertHasNoErrors()
                ->assertRedirect(route('projects.globus.drops.index', [$project]));

        $drop->refresh();

        $this->assertSame('starting import', $drop->status);
        $this->assertSame($rootDirectory->id, $drop->directory_id);

        Bus::assertDispatched(ImportGlobusDropFilesJob::class, function (ImportGlobusDropFilesJob $job) use ($drop, $rootDirectory) {
            return $job->drop->is($drop)
                && $job->dirPath === $rootDirectory->path;
        });
    }

    public function test_import_globus_drop_files_job_does_nothing_when_drop_status_is_not_starting_import(): void
    {
        Storage::fake('mcfs');

        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $drop = GlobusDrop::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'name'       => 'Not Ready Drop',
            'status'     => 'ready for import',
        ]);

        Storage::disk('mcfs')->makeDirectory("__globus_uploads/drops/{$drop->id}/{$drop->name}");
        Storage::disk('mcfs')->put("__globus_uploads/drops/{$drop->id}/{$drop->name}/file.txt", 'file contents');

        $job = new ImportGlobusDropFilesJob($drop, '/');
        $job->handle();

        $this->assertDatabaseHas('globus_drops', [
            'id'     => $drop->id,
            'status' => 'ready for import',
        ]);

        Storage::disk('mcfs')->assertExists("__globus_uploads/drops/{$drop->id}/{$drop->name}/file.txt");
    }

    public function test_import_globus_drop_files_job_imports_files_and_deletes_drop_when_status_is_starting_import(): void
    {
        Storage::fake('mcfs');

        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $drop = GlobusDrop::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'name'       => 'Starting Import Drop',
            'status'     => 'starting import',
        ]);

        Storage::disk('mcfs')->makeDirectory("__globus_uploads/drops/{$drop->id}/{$drop->name}");
        Storage::disk('mcfs')->put("__globus_uploads/drops/{$drop->id}/{$drop->name}/file.txt", 'file contents');

        $job = new ImportGlobusDropFilesJob($drop, '/');
        $job->handle();

        $this->assertDatabaseMissing('globus_drops', [
            'id' => $drop->id,
        ]);

        $this->assertDatabaseHas('files', [
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'name'       => 'file.txt',
        ]);

        Storage::disk('mcfs')->assertMissing("__globus_uploads/drops/{$drop->id}");
    }

    public function test_delete_drop_removes_drop_and_redirects_to_index(): void
    {
        Storage::fake('mcfs');

        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $drop = GlobusDrop::factory()->create([
            'project_id'         => $project->id,
            'owner_id'           => $user->id,
            'globus_path'        => '/__globus_uploads/drops/123/drop/',
            'globus_endpoint_id' => 'test-endpoint-id',
        ]);

        Storage::disk('mcfs')->makeDirectory("__globus_uploads/drops/{$drop->id}");

        $this->mockGlobusApi();

        $this->actingAs($user)
             ->get(route('projects.globus.drops.delete', [$project, $drop]))
             ->assertRedirect(route('projects.globus.drops.index', [$project]));

        $this->assertDatabaseMissing('globus_drops', ['id' => $drop->id]);
        Storage::disk('mcfs')->assertMissing("__globus_uploads/drops/{$drop->id}");
    }

    public function test_delete_drop_for_different_project_does_not_delete_drop(): void
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $otherProject = Project::factory()->create(['owner_id' => $user->id]);

        $drop = GlobusDrop::factory()->create([
            'project_id' => $otherProject->id,
            'owner_id'   => $user->id,
        ]);

        $this->mockGlobusApi();

        $this->actingAs($user)
             ->get(route('projects.globus.drops.delete', [$project, $drop]))
             ->assertRedirect(route('projects.globus.drops.index', [$project]));

        $this->assertDatabaseHas('globus_drops', ['id' => $drop->id]);
    }

    public function test_mark_drop_transfer_done_updates_status_queues_email_and_shows_done_page(): void
    {
        Mail::fake();

        $owner = User::factory()->create(['email' => 'owner@example.com']);

        $drop = GlobusDrop::factory()->create([
            'owner_id'           => $owner->id,
            'uuid'               => 'drop-uuid',
            'status'             => 'available',
            'globus_endpoint_id' => 'test-endpoint-id',
            'globus_path'        => '/__globus_uploads/drops/1/drop/',
        ]);

        $this->mockGlobusApi();

        $this->post(route('globus-drop.mark-done', [$drop->uuid]), [
            'message' => 'Transfer is complete.',
        ])
             ->assertOk()
             ->assertViewIs('app.projects.globus.drops.inform-done')
             ->assertViewHas('drop', $drop);

        $this->assertSame('ready for import', $drop->fresh()->status);

        Mail::assertQueued(DropReadyForImportMail::class, function (DropReadyForImportMail $mail) use ($owner) {
            return $mail->hasTo($owner->email);
        });
    }

    public function test_mark_drop_transfer_done_shows_not_found_page_when_drop_uuid_is_unknown(): void
    {
        $this->post(route('globus-drop.mark-done', ['unknown-drop-uuid']))
             ->assertOk()
             ->assertViewIs('app.projects.globus.drops.not-found');
    }

    private function mockGlobusApi(): void
    {
        $globusApi = Mockery::mock(GlobusApi::class);

        $globusApi->shouldReceive('getIdentities')
                  ->zeroOrMoreTimes()
                  ->andReturnUsing(function (array $emails) {
                      return [
                          'identities' => [
                              [
                                  'id'     => 'identity-'.md5($emails[0]),
                                  'status' => 'used',
                              ],
                          ],
                      ];
                  });

        $globusApi->shouldReceive('addEndpointAclRule')
                  ->zeroOrMoreTimes()
                  ->andReturn(['access_id' => 'test-access-id']);

        $globusApi->shouldReceive('deleteACLsOnPath')
                  ->zeroOrMoreTimes()
                  ->andReturnNull();

        $globusApi->shouldReceive('getEndpointAccessRules')
                  ->zeroOrMoreTimes()
                  ->andReturn([
                      'DATA' => [],
                  ]);

        $globusApi->shouldReceive('deleteEndpointAclRule')
                  ->zeroOrMoreTimes()
                  ->andReturn([]);

        $this->app->instance(GlobusApi::class, $globusApi);
    }
}
