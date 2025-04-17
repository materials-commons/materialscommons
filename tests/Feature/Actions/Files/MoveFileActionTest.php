<?php

namespace Tests\Feature\Actions\Files;

use App\Actions\Files\MoveFileAction;
use App\Models\File;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoveFileActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_move_a_file_to_an_empty_directory_and_update_its_attributes()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $directory = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $originalFile = ProjectFactory::createFakeFile($project, $project->rootDir, "file.txt");

        $moveFileAction = new MoveFileAction();
        $moveFileAction($originalFile, $directory, $user);

        $this->assertEquals(1, File::where('directory_id', $directory->id)->count());
        $movedFile = $originalFile->fresh();

        // Verify the file's attributes after moving
        $this->assertEquals("file.txt", $movedFile->name);
        $this->assertEquals($directory->project_id, $movedFile->project_id);
        $this->assertEquals($directory->id, $movedFile->directory_id);
        $this->assertEquals($user->id, $movedFile->owner_id);
        $this->assertTrue($movedFile->current);
    }

    /** @test */
    public function it_should_move_a_file_when_a_file_with_same_name_exists_and_mark_it_as_inactive()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $directory = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $existingFile = ProjectFactory::createFakeFile($project, $directory, "file.txt");
        $fileToMove = ProjectFactory::createFakeFile($project, $project->rootDir, "file.txt");

        $moveFileAction = new MoveFileAction();
        $moveFileAction($fileToMove, $directory, $user);

        $this->assertEquals(1, File::where('current', true)->where('name', 'file.txt')->count());
        $this->assertEquals(1, File::where('current', false)->where('name', 'file.txt')->count());
        $fileToMove->refresh();
        $this->assertEquals($directory->id, $fileToMove->directory_id);
        $this->assertTrue($fileToMove->current);
        $existingFile->refresh();
        $this->assertFalse($existingFile->current);
    }

    /** @test */
    public function it_should_move_a_file_to_a_project_where_user_has_access()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $fileToMove = ProjectFactory::createFakeFile($project, $project->rootDir, "file.txt");

        $user2 = User::factory()->create();
        $otherProject = ProjectFactory::ownedBy($user2)->create();
        ProjectFactory::addMemberToProject($user, $otherProject);

        $moveFileAction = new MoveFileAction();

        // User has access, moving the file should succeed
        $this->assertNotNull($moveFileAction($fileToMove, $otherProject->rootDir, $user));

        $this->assertEquals(1, File::where('directory_id', $otherProject->rootDir->id)->count());
        $movedFile = File::where('directory_id', $otherProject->rootDir->id)->first();

        // Verify the file's attributes after the move
        $this->assertEquals("file.txt", $movedFile->name);
        $this->assertEquals($otherProject->id, $movedFile->project_id);
        $this->assertEquals($otherProject->rootDir->id, $movedFile->directory_id);
        $this->assertEquals($user->id, $movedFile->owner_id);
    }
}
