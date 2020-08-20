<?php

namespace Tests\Feature\Actions\Globus\Uploads;

use App\Actions\Globus\Uploads\ImportGlobusUploadIntoProjectAction;
use App\Models\File;
use App\Models\GlobusUploadDownload;
use App\Models\Project;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Utils\GlobusMockUtils;

class ImportGlobusUploadIntoProjectActionTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        $globusUploadsPath = Storage::disk('mcfs')->path("__globus_uploads");
        exec("rm -rf {$globusUploadsPath}");

        $this->cleanupCopiedFiles();
    }

    /** @test */
    public function files_and_directories_should_be_loaded_into_project()
    {
        $globusUploadsPath = Storage::disk('mcfs')->path("__globus_uploads");
        $this->Mkdir($globusUploadsPath);

        $user = factory(User::class)->create();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $rootDir = factory(File::class)->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        $globusUpload = factory(GlobusUploadDownload::class)->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);

        $from = Storage::disk('test_data')->path('globus/test1');
        $cmd = "cp -r {$from} {$globusUploadsPath}/{$globusUpload->uuid}";
        exec($cmd);
        $globusUpload->update(['path' => Storage::disk('mcfs')->path("__globus_uploads/{$globusUpload->uuid}")]);

        $globusApiMock = GlobusMockUtils::createGlobusApiMock();
        $importGlobusUploadIntoProjectAction = new ImportGlobusUploadIntoProjectAction($globusUpload, 10,
            $globusApiMock);
        $importGlobusUploadIntoProjectAction();

        $filesCount = File::where('project_id', $project->id)->whereNull('path')->count();
        $this->assertEquals(3, $filesCount);

        $dirCount = File::where('project_id', $project->id)->whereNotNull('path')->count();
        $this->assertEquals(3, $dirCount);
    }

    /** @test */
    public function existing_directories_should_not_be_recreated()
    {
        $this->fail("Not implemented");
    }

    /** @test */
    public function existing_files_should_have_new_versions()
    {
        $this->fail("Not implemented");
    }

    /** @test */
    public function uploading_a_file_with_same_name_as_directory_should_skip_processing_the_file()
    {
        $project = ProjectFactory::create();
        $globusUpload = $this->setupGlobusUpload('globus/test1', $project->id, $project->owner_id);

        // There is a file named root.txt so in files to upload so lets create a directory with the same name.
        // For this test we should see that the file (root.txt) does not get created/uploaded.
        ProjectFactory::createDirectory($project, $project->rootDir, "root.txt");

        // Now attempt to do the upload
        $globusApiMock = GlobusMockUtils::createGlobusApiMock();
        $importGlobusUploadIntoProjectAction = new ImportGlobusUploadIntoProjectAction($globusUpload, 10,
            $globusApiMock);
        $importGlobusUploadIntoProjectAction();

        // There should still be a directory named root.txt
        $this->assertDatabaseHas('files', [
            'mime_type'  => 'directory',
            'path'       => '/root.txt',
            'name'       => 'root.txt',
            'project_id' => $project->id,
        ]);

        // There should not be a file named root.txt
        $this->assertDatabaseMissing('files', [
            'name'       => 'root.txt',
            'project_id' => $project->id,
            'path'       => null,
            'mime_type'  => 'text/plain',
        ]);

        // Double check the count there should only be one entry named root.txt
        $this->assertEquals(1,
            File::where('name', 'root.txt')
                ->where('project_id', $project->id)->count());
    }

    /** @test */
    public function uploading_a_directory_with_the_name_name_as_a_file_should_skip_processing_the_directory()
    {
        $project = ProjectFactory::create();
        $globusUpload = $this->setupGlobusUpload('globus/test1', $project->id, $project->owner_id);

        // There is a directory named d1 in the files to upload so in project lets create a file with the same name.
        // For this test we should see that the directory does not get created.
        ProjectFactory::createFakeFile($project, $project->rootDir, "d1");

        // Now attempt to do the upload
        $globusApiMock = GlobusMockUtils::createGlobusApiMock();
        $importGlobusUploadIntoProjectAction = new ImportGlobusUploadIntoProjectAction($globusUpload, 10,
            $globusApiMock);
        $importGlobusUploadIntoProjectAction();

        // There should still be a file named d1
        $this->assertDatabaseHas('files', [
            'name'       => 'd1',
            'mime_type'  => 'text',
            'path'       => null,
            'project_id' => $project->id,
        ]);

        // There should not be a directory named d1
        $this->assertDatabaseMissing('files', [
            'name'      => 'd1',
            'mime_type' => 'directory',
        ]);

        // Double check the count there should only be one entry named root.txt
        $this->assertEquals(1,
            File::where('name', 'd1')
                ->where('project_id', $project->id)->count());
    }

    ////////////// Utility functions for test //////////////////

    private function Mkdir($path)
    {
        if (!is_dir($path)) {
            mkdir($path);
        }
    }

    private function setupGlobusUpload($pathPartial, $projectId, $userId)
    {
        $globusUploadsPath = Storage::disk('mcfs')->path("__globus_uploads");
        $this->Mkdir($globusUploadsPath);

        $globusUpload = factory(GlobusUploadDownload::class)->create([
            'project_id' => $projectId,
            'owner_id'   => $userId,
        ]);

        $from = Storage::disk('test_data')->path($pathPartial);
        $cmd = "cp -r {$from} {$globusUploadsPath}/{$globusUpload->uuid}";
        exec($cmd);
        $globusUpload->update(['path' => Storage::disk('mcfs')->path("__globus_uploads/{$globusUpload->uuid}")]);
        return $globusUpload;
    }

    private function cleanupCopiedFiles()
    {
        foreach (Storage::disk('local')->directories() as $dir) {
            if ($dir !== 'public') {
                Storage::disk('local')->deleteDirectory($dir);
            }
        }
    }
}
