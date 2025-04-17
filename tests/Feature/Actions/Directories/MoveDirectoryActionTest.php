<?php

namespace Tests\Feature\Actions\Directories;

use App\Actions\Directories\MoveDirectoryAction;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
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
            'current' => true,
        ]);

        $dir1 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir1',
            'path'         => '/dir1',
            'mime_type'    => 'directory',
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
            'current' => true,
        ]);

        $dir2 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir2',
            'path'         => '/dir2',
            'mime_type'    => 'directory',
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
            'current' => true,
        ]);

        $dir21 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir21',
            'path'         => '/dir2/dir21',
            'mime_type'    => 'directory',
            'directory_id' => $dir2->id,
            'owner_id'     => $user->id,
            'current' => true,
        ]);

        $dir211 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir211',
            'path'         => '/dir2/dir21/dir211',
            'mime_type'    => 'directory',
            'directory_id' => $dir21->id,
            'owner_id'     => $user->id,
            'current' => true,
        ]);

        $moveDirectoryAction = new MoveDirectoryAction();
        $moveDirectoryAction($dir2->id, $dir1->id, $user);
        $dir2->refresh();
        $dir21->refresh();
        $dir211->refresh();
        $this->assertEquals($dir2->directory_id, $dir1->id);
        $this->assertEquals("/dir1/dir2", $dir2->path);
        $this->assertEquals("/dir1/dir2/dir21", $dir21->path);
        $this->assertEquals("/dir1/dir2/dir21/dir211", $dir211->path);
        $this->assertEquals($dir21->directory_id, $dir2->id);
    }

    /** @test */
    public function test_it_should_move_files_but_versions_should_not_become_latest()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);

        // setup directories
        $rootDir = File::factory()->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
            'current'    => true,
        ]);

        $dir1 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir1',
            'path'         => '/dir1',
            'mime_type'    => 'directory',
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
            'current'      => true,
        ]);

        $dir2 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir2',
            'path'         => '/dir2',
            'mime_type'    => 'directory',
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
            'current'      => true,
        ]);

        $dir21 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir21',
            'path'         => '/dir2/dir21',
            'mime_type'    => 'directory',
            'directory_id' => $dir2->id,
            'owner_id'     => $user->id,
            'current'      => true,
        ]);

        $dir211 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir211',
            'path'         => '/dir2/dir21/dir211',
            'mime_type'    => 'directory',
            'directory_id' => $dir21->id,
            'owner_id'     => $user->id,
            'current'      => true,
        ]);

        // Create a file with multiple versions, then test what happens when we move it
        $file = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'file1.txt',
            'directory_id' => $dir211->id,
            'owner_id'     => $user->id,
            'current'      => true,
        ]);

        $fileOldVersion = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'file1.txt',
            'directory_id' => $dir211->id,
            'owner_id'     => $user->id,
            'current'      => false,
        ]);

        $moveDirectoryAction = new MoveDirectoryAction();
        $moveDirectoryAction($dir2->id, $dir1->id, $user);
        $dir2->refresh();
        $dir21->refresh();
        $dir211->refresh();
        // Check that dirs moved
        $this->assertEquals($dir2->directory_id, $dir1->id);
        $this->assertEquals("/dir1/dir2", $dir2->path);
        $this->assertEquals("/dir1/dir2/dir21", $dir21->path);
        $this->assertEquals("/dir1/dir2/dir21/dir211", $dir211->path);
        $this->assertEquals($dir21->directory_id, $dir2->id);

        // Check that files didn't change
        $file->refresh();
        $fileOldVersion->refresh();
        $this->assertEquals($file->directory_id, $dir211->id);
        $this->assertEquals($fileOldVersion->directory_id, $dir211->id);
        $this->assertTrue($file->current);
        $this->assertFalse($fileOldVersion->current);
    }

    /** @test */
    public function test_it_should_set_project_when_moving_to_another_project()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $project2 = ProjectFactory::ownedBy($user)->create();

        $rootDirProject1 = File::factory()->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
            'current'    => true,
        ]);

        $rootDirProject2 = File::factory()->create([
            'project_id' => $project2->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
            'current'    => true,
        ]);

        $dir1Proj1 = File::factory()->create([
            'directory_id' => $rootDirProject1->id,
            'project_id'   => $project->id,
            'name'         => 'dir1Proj1',
            'path'         => '/dir1Proj1',
            'mime_type'    => 'directory',
            'owner_id'     => $user->id,
            'current'      => true,
        ]);

        $dir1Proj2 = File::factory()->create([
            'directory_id' => $rootDirProject2->id,
            'project_id'   => $project2->id,
            'name'         => 'dir1Proj2',
            'path'         => '/dir1Proj2',
            'mime_type'    => 'directory',
            'owner_id'     => $user->id,
            'current'      => true,
        ]);

        // Add a file to the directory we are going to move to a different project
        $file = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'file1.txt',
            'directory_id' => $dir1Proj1->id,
            'owner_id'     => $user->id,
            'current'      => true,
        ]);

        $moveDirectoryAction = new MoveDirectoryAction();
        $d = $moveDirectoryAction($dir1Proj1->id, $dir1Proj2->id, $user);
        $this->assertNotNull($d);
        $dir1Proj1->refresh();
        $dir1Proj2->refresh();
        $file->refresh();
        // Check that $dir1Proj1 was moved to dir1Proj2. The directory id for dir1Proj1
        // should be equal to the dir1Proj2 id, since it now sits under dir1Proj2.
        $this->assertEquals($dir1Proj2->id, $dir1Proj1->directory_id);
        $this->assertEquals($dir1Proj1->path, "/dir1Proj2/dir1Proj1");

        // The project_id for dir1Proj1 should have been changed to $project2->id
        $this->assertEquals($project2->id, $dir1Proj1->project_id);

        // The file project_id in dir1Proj1 should have been changed to $project2->id
        $this->assertEquals($project2->id, $file->project_id);
    }
}
