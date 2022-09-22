<?php

namespace Tests\Feature\Actions\Directories;

use App\Actions\Directories\CopyDirectoryAction;
use App\Models\File;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CopyDirectoryActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_copy_a_simple_directory_to_another_directory_and_all_have_correct_attributes()
    {
        $user = User::factory()->create();
        $project = ProjectFactory::ownedBy($user)->create();

        $d1 = ProjectFactory::createDirectory($project, $project->rootDir, "d1");
        $d1File = ProjectFactory::createFakeFile($project, $d1, "file.txt");

        $destDir = ProjectFactory::createDirectory($project, $project->rootDir, "destdir");

        $copyDirAction = new CopyDirectoryAction();
        $this->assertTrue($copyDirAction->execute($d1, $destDir, $user));
        $this->assertEquals(1, File::where('directory_id', $destDir->id)->count());
    }

    /** @test */
    public function it_should_copy_a_deeper_directory_to_another_directory()
    {
        $this->fail("not implemented");
    }

    /** @test */
    public function it_should_fail_to_copy_when_directory_with_same_name_exists()
    {
        $this->fail("not implemented");
    }

    /** @test */
    public function it_should_fail_to_copy_dir_to_a_different_project_user_is_not_in()
    {
        $this->fail("not implemented");
    }

    /** @test */
    public function it_should_copy_dir_to_a_different_project_user_is_in()
    {
        $this->fail("not implemented");
    }
}
