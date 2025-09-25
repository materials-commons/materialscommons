<?php

namespace Tests\Feature\Actions\Files;

use App\Actions\Files\CreateFileAction;
use App\Models\File;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CreateFileActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_only_create_a_single_version_of_file_when_uploaded_twice()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = ProjectFactory::ownedBy($user)->create();
        $rootDir = $project->rootDir;

        $fakeFile = UploadedFile::fake()->image('random.jpg');

        $createFileAction = new CreateFileAction();
        $f = $createFileAction($project, $rootDir, "", $fakeFile, 'web');
        $f2 = $createFileAction($project, $rootDir, "", $fakeFile, 'web');

        $this->assertEquals($f->id, $f2->id);
        $this->assertDatabaseCount("files", 2);
        $this->assertDatabaseHas("files", ['name' => "random.jpg"]);
        $this->assertEquals(1, File::where('mime_type', 'directory')->count());
        $this->assertEquals(1, File::where('mime_type', '<>', 'directory')->count());
    }

    /** @test */
    public function it_should_upload_two_files()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = ProjectFactory::ownedBy($user)->create();
        $rootDir = $project->rootDir;

        $fake1 = UploadedFile::fake()->image('random1.jpg');
        $fake2 = UploadedFile::fake()->image('random2.jpg');

        $createFileAction = new CreateFileAction();
        $f = $createFileAction($project, $rootDir, "", $fake1, 'web');
        $f2 = $createFileAction($project, $rootDir, "", $fake2, 'web');

        $this->assertNotEquals($f->id, $f2->id);
        $this->assertDatabaseCount("files", 3);
        $this->assertDatabaseHas("files", ['name' => "random1.jpg"]);
        $this->assertDatabaseHas("files", ['name' => "random2.jpg"]);
        $this->assertEquals(1, File::where('mime_type', 'directory')->count());
        $this->assertEquals(2, File::where('mime_type', '<>', 'directory')->count());
    }
}
