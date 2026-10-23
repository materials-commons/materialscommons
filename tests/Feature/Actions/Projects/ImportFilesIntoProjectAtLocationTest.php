<?php

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\ImportFilesIntoProjectAtLocationAction;
use App\Models\File;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Utils\StorageUtils;

class ImportFilesIntoProjectAtLocationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_import_files_into_project(): void
    {
        $project = ProjectFactory::create();
        $project->load('owner');
        $user = $project->owner;

        $this->assertTrue(Storage::disk('script_runs_out')->makeDirectory('abc123'));
        $this->assertTrue(Storage::disk('script_runs_out')->put('abc123/file.txt', "file contents"));
        $this->assertTrue(Storage::disk('script_runs_out')->makeDirectory('abc123/dir1'));
        $this->assertTrue(Storage::disk('script_runs_out')->put("abc123/dir1/file1.txt", "file1 contents"));

        $action = new ImportFilesIntoProjectAtLocationAction();
        $action->execute($project, 'script_runs_out', 'abc123', $user);
        foreach (File::all() as $file) {
            if ($file->isFile()) {
                $this->assertTrue(\File::exists($file->mcfsPath()));
            }
        }
        StorageUtils::clearStorage();
    }

    #[Test]
    public function it_should_import_files_into_a_project_directory(): void
    {
        $project = ProjectFactory::create();
        $project->load('owner');
        $user = $project->owner;

        $this->assertTrue(Storage::disk('script_runs_out')->makeDirectory('abc123'));
        $this->assertTrue(Storage::disk('script_runs_out')->put('abc123/file.txt', "file contents"));
        $this->assertTrue(Storage::disk('script_runs_out')->makeDirectory('abc123/dir1'));
        $this->assertTrue(Storage::disk('script_runs_out')->put("abc123/dir1/file1.txt", "file1 contents"));

        $action = new ImportFilesIntoProjectAtLocationAction();
        $action->execute($project, 'script_runs_out', 'abc123', $user, null, '/d');

        $this->assertNotNull(File::where('project_id', $project->id)->directories()->where('path', '/d')->first());
        $this->assertNotNull(File::where('project_id', $project->id)->files()->where('name', 'file.txt')->whereHas('directory', function ($query) {
            $query->where('path', '/d');
        })->first());
        $this->assertNotNull(File::where('project_id', $project->id)->directories()->where('path', '/d/dir1')->first());
        $this->assertNotNull(File::where('project_id', $project->id)->files()->where('name', 'file1.txt')->whereHas('directory', function ($query) {
            $query->where('path', '/d/dir1');
        })->first());

        foreach (File::all() as $file) {
            if ($file->isFile()) {
                $this->assertTrue(\File::exists($file->mcfsPath()));
            }
        }

        StorageUtils::clearStorage();
    }
}
