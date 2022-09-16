<?php

namespace Tests\Feature\Actions\Files;

use App\Actions\Files\CopyFileAction;
use App\Models\File;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CopyFileActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_copy_a_file_to_an_empty_directory_and_have_correct_attributes()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $d1 = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $rootFile = ProjectFactory::createFakeFile($project, $project->rootDir, "file.txt");

        $copyFileAction = new CopyFileAction();
        $this->assertTrue($copyFileAction->execute($rootFile, $d1, $user));

        $this->assertEquals(1, File::where('directory_id', $d1->id)->count());
        $copiedFile = File::where('directory_id', $d1->id)->first();

        // Make sure the copied file has the correct attributes. Very importantly
        // make sure that uses_uuid is correctly set.
        $this->assertEquals("file.txt", $copiedFile->name);
        $this->assertNotEquals($rootFile->uuid, $copiedFile->uuid);
        $this->assertEquals($d1->project_id, $copiedFile->project_id);
        $this->assertEquals($d1->id, $copiedFile->directory_id);
        $this->assertEquals($user->id, $copiedFile->owner_id);
        $this->assertEquals($rootFile->size, $copiedFile->size);
        $this->assertEquals($rootFile->checksum, $copiedFile->checksum);
        $this->assertTrue($copiedFile->current);
        $this->assertEquals($rootFile->disk, $copiedFile->disk);
        $this->assertEquals($rootFile->mime_type, $copiedFile->mime_type);
        $this->assertEquals($rootFile->media_type_description, $copiedFile->media_type_description);
        $this->assertEquals($rootFile->uuid, $copiedFile->uses_uuid);
    }

    /** @test */
    public function it_should_fail_to_copy_a_file_when_one_with_same_name_exists()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $rootFile = ProjectFactory::createFakeFile($project, $project->rootDir, "file.txt");

        $copyFileAction = new CopyFileAction();

        // Attempt to copy file over itself (ie, copy to same directory file is in without
        // changing its name). This should fail.
        $this->assertFalse($copyFileAction->execute($rootFile, $project->rootDir, $user));
    }

    /** @test */
    public function it_should_fail_to_copy_a_file_to_a_different_project_user_is_not_in()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $fileToCopy = ProjectFactory::createFakeFile($project, $project->rootDir, "file.txt");

        $user2 = User::factory()->create();
        $project2 = ProjectFactory::ownedBy($user2)->create();

        $copyFileAction = new CopyFileAction();

        // $user is not in $project2, so copying $fileToCopy, which is in $project, into
        // the rootDir for $project2 should fail.
        $this->assertFalse($copyFileAction->execute($fileToCopy, $project2->rootDir, $user));
    }

    /** @test */
    public function it_should_copy_a_file_to_a_different_project_user_can_access()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();
        $fileToCopy = ProjectFactory::createFakeFile($project, $project->rootDir, "file.txt");

        $user2 = User::factory()->create();
        $project2 = ProjectFactory::ownedBy($user2)->create();
        ProjectFactory::addMemberToProject($user, $project2);

        $copyFileAction = new CopyFileAction();

        // $user is in $project2. We should be able to copy $fileToCopy to the rootDir
        // of $project2
        $this->assertTrue($copyFileAction->execute($fileToCopy, $project2->rootDir, $user));
        $this->assertEquals(1, File::where('directory_id', $project2->rootDir->id)->count());
        $copiedFile = File::where('directory_id', $project2->rootDir->id)->first();

        // Let's make sure the copied file has the correct project_id and directory_id
        $this->assertEquals($project2->id, $copiedFile->project_id);
        $this->assertEquals($project2->rootDir->id, $copiedFile->directory_id);
    }
}
