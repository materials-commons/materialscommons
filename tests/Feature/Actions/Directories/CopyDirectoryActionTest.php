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
    public function it_should_copy_a_directory_to_another_directory_and_all_have_correct_attributes()
    {
        $copyDirAction = new CopyDirectoryAction();
//        $copyDirAction->execute();
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
