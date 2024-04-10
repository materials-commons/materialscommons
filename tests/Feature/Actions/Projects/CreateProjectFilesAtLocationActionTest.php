<?php

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\CreateProjectFilesAtLocationAction;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Utils\StorageUtils;

use Illuminate\Support\Str;

class CreateProjectFilesAtLocationActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function is_creates_all_files_and_directories_in_project(): void
    {
        $project = ProjectFactory::create();
        ProjectFactory::createFile($project, $project->rootDir, "test.txt", "test");
        $rootDir = $project->rootDir;
        $user = $project->owner;

        $dir1 = ProjectFactory::createDirectory($project, $rootDir, "dir1");
        ProjectFactory::createFile($project, $dir1, "dir1test.txt", "dir1test");

        $dir11 = ProjectFactory::createDirectory($project, $dir1, "dir11");
        ProjectFactory::createFile($project, $dir11, "dir11test.txt", "dir11test");

        $action = new CreateProjectFilesAtLocationAction();
        $action->execute($project, "mcfs", "__script_runs/abc123");

        Storage::disk("mcfs")->assertExists("__script_runs/abc123/test.txt");
        Storage::disk("mcfs")->assertExists("__script_runs/abc123/dir1");
        Storage::disk("mcfs")->assertExists("__script_runs/abc123/dir1/dir1test.txt");
        Storage::disk("mcfs")->assertExists("__script_runs/abc123/dir1/dir11");
        Storage::disk("mcfs")->assertExists("__script_runs/abc123/dir1/dir11/dir11test.txt");

        StorageUtils::clearStorage();
    }
}
