<?php

namespace Tests\Feature\Actions\Directories;

use App\Actions\Directories\MoveDirectoryAction;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoveDirectoryActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_move_the_directory_and_recursive_subdirs_including_updating_path()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $project = Project::factory()->create(['owner_id' => $user->id]);

        $rootDir = File::factory()->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        $dir1 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir1',
            'path'         => '/dir1',
            'mime_type'    => 'directory',
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
        ]);

        $dir2 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir2',
            'path'         => '/dir2',
            'mime_type'    => 'directory',
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
        ]);

        $dir21 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir21',
            'path'         => '/dir2/dir21',
            'mime_type'    => 'directory',
            'directory_id' => $dir2->id,
            'owner_id'     => $user->id,
        ]);

        $dir211 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir211',
            'path'         => '/dir2/dir21/dir211',
            'mime_type'    => 'directory',
            'directory_id' => $dir21->id,
            'owner_id'     => $user->id,
        ]);
        
        $moveDirectoryAction = new MoveDirectoryAction();
        $moveDirectoryAction($dir2->id, $dir1->id);
        $dir2->refresh();
        $dir21->refresh();
        $dir211->refresh();
        $this->assertEquals($dir2->directory_id, $dir1->id);
        $this->assertEquals("/dir1/dir2", $dir2->path);
        $this->assertEquals("/dir1/dir2/dir21", $dir21->path);
        $this->assertEquals("/dir1/dir2/dir21/dir211", $dir211->path);
        $this->assertEquals($dir21->directory_id, $dir2->id);
    }
}
