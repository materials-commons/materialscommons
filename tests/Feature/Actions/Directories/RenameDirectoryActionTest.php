<?php

namespace Tests\Feature\Actions\Directories;

use App\Actions\Directories\RenameDirectoryAction;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RenameDirectoryActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_rename_the_directory_and_recursive_subdirs_including_path()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $rootDir = factory(File::class)->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        $dir1 = factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => 'dir1',
            'path'         => '/dir1',
            'mime_type'    => 'directory',
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
        ]);

        $dir11 = factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => 'dir11',
            'path'         => '/dir1/dir11',
            'mime_type'    => 'directory',
            'directory_id' => $dir1->id,
            'owner_id'     => $user->id,
        ]);

        $dir111 = factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => 'dir111',
            'path'         => '/dir1/dir11/dir111',
            'mime_type'    => 'directory',
            'directory_id' => $dir11->id,
            'owner_id'     => $user->id,
        ]);

        $renameDirectoryAction = new RenameDirectoryAction();
        $renameDirectoryAction($dir1->id, "dir1-changed");
        $dir1->refresh();
        $dir11->refresh();
        $dir111->refresh();
        $this->assertEquals("dir1-changed", $dir1->name);
        $this->assertEquals("/dir1-changed", $dir1->path);
        $this->assertEquals("/dir1-changed/dir11", $dir11->path);
        $this->assertEquals("/dir1-changed/dir11/dir111", $dir111->path);
    }
}
