<?php

namespace Tests\Feature\Livewire\Datasets;

use App\Livewire\Datasets\FileSelection;
use App\Models\Dataset;
use App\Models\File;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FileSelectionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_files_and_directories(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $rootDir = $project->rootDir;

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $file = File::factory()->create([
            'project_id'   => $project->id,
            'owner_id'     => $user->id,
            'directory_id' => $rootDir->id,
            'name'         => 'example.txt',
            'mime_type'    => 'text/plain',
        ]);

        $directory = File::factory()->create([
            'project_id'   => $project->id,
            'owner_id'     => $user->id,
            'directory_id' => $rootDir->id,
            'name'         => 'data',
            'path'         => '/data',
            'mime_type'    => 'directory',
        ]);

        $this->actingAs($user);

        Livewire::test(FileSelection::class, [
            'project'                  => $project,
            'dataset'                  => $dataset,
            'directory'                => $rootDir,
            'files'                    => collect([$file, $directory]),
            'dirPaths'                 => [
                ['name' => '/', 'path' => '/'],
            ],
            'directoryPathRouteName'   => 'projects.datasets.files.edit',
            'addFilesRouteName'        => 'projects.datasets.create-data.upload-files',
            'createDirectoryRouteName' => 'projects.datasets.create-data.create-directory',
        ])
                ->assertSee('example.txt')
                ->assertSee('data')
                ->assertSeeHtml('id="'.$file->uuid.'"')
                ->assertSeeHtml('id="'.$directory->uuid.'"')
                ->assertSeeHtml('type="checkbox"')
                ->assertSee('Files will be added or removed automatically as you select them.');
    }

    #[Test]
    public function it_toggles_regular_file_selection(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id'     => $project->id,
            'owner_id'       => $user->id,
            'file_selection' => [
                'include_files' => [],
                'include_dirs'  => [],
            ],
        ]);

        $rootDir = $project->rootDir;

        $file = File::factory()->create([
            'project_id'   => $project->id,
            'owner_id'     => $user->id,
            'directory_id' => $rootDir->id,
            'name'         => 'example.txt',
            'mime_type'    => 'text/plain',
        ]);

        $this->actingAs($user);

        Livewire::test(FileSelection::class, [
            'project'                  => $project,
            'dataset'                  => $dataset,
            'directory'                => $rootDir,
            'files'                    => collect([$file]),
            'dirPaths'                 => [
                ['name' => '/', 'path' => '/'],
            ],
            'directoryPathRouteName'   => 'projects.datasets.files.edit',
            'addFilesRouteName'        => 'projects.datasets.create-data.upload-files',
            'createDirectoryRouteName' => 'projects.datasets.create-data.create-directory',
        ])
                ->call('toggleFile', $file->id);

        $dataset->refresh();

        $this->assertContains('/example.txt', $dataset->file_selection['include_files'] ?? []);
    }

    #[Test]
    public function it_toggles_directory_selection(): void
    {
        $user = User::factory()->create();

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id'     => $project->id,
            'owner_id'       => $user->id,
            'file_selection' => [
                'include_files' => [],
                'include_dirs'  => [],
            ],
        ]);

        $rootDir = $project->rootDir;

        $directory = File::factory()->create([
            'project_id'   => $project->id,
            'owner_id'     => $user->id,
            'directory_id' => $rootDir->id,
            'name'         => 'data',
            'path'         => '/data',
            'mime_type'    => 'directory',
        ]);

        $this->actingAs($user);

        Livewire::test(FileSelection::class, [
            'project'                  => $project,
            'dataset'                  => $dataset,
            'directory'                => $rootDir,
            'files'                    => collect([$directory]),
            'dirPaths'                 => [
                ['name' => '/', 'path' => '/'],
            ],
            'directoryPathRouteName'   => 'projects.datasets.files.edit',
            'addFilesRouteName'        => 'projects.datasets.create-data.upload-files',
            'createDirectoryRouteName' => 'projects.datasets.create-data.create-directory',
        ])
                ->call('toggleFile', $directory->id);

        $dataset->refresh();

        $this->assertContains('/data', $dataset->file_selection['include_dirs'] ?? []);
    }

    #[Test]
    public function it_does_not_render_api_token(): void
    {
        $user = User::factory()->create([
            'api_token' => 'plain-text-token-that-should-not-render',
        ]);

        $project = ProjectFactory::ownedBy($user)->create();

        $dataset = Dataset::factory()->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $rootDir = $project->rootDir;

        $this->actingAs($user);

        Livewire::test(FileSelection::class, [
            'project'                  => $project,
            'dataset'                  => $dataset,
            'directory'                => $rootDir,
            'files'                    => collect(),
            'dirPaths'                 => [
                ['name' => '/', 'path' => '/'],
            ],
            'directoryPathRouteName'   => 'projects.datasets.files.edit',
            'addFilesRouteName'        => 'projects.datasets.create-data.upload-files',
            'createDirectoryRouteName' => 'projects.datasets.create-data.create-directory',
        ])
                ->assertDontSee('plain-text-token-that-should-not-render')
                ->assertDontSee('api_token');
    }
}
