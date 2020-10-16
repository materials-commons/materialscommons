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
        $project = ProjectFactory::create();
        $globusUpload = $this->setupGlobusUpload('globus/test1', $project->id, $project->owner_id);

        // There is a directory /d1 that will be uploaded. Lets create an existing one to make sure that a
        // second one is not created.
        $d1 = ProjectFactory::createDirectory($project, $project->rootDir, "d1");

        $globusApiMock = GlobusMockUtils::createGlobusApiMock();
        $importGlobusUploadIntoProjectAction = new ImportGlobusUploadIntoProjectAction($globusUpload, 10,
            $globusApiMock);
        $importGlobusUploadIntoProjectAction();

        // Ensure that d1 still exists and that there is only one directory named d1 (a second was *not* created)

        // Existence check
        $this->assertDatabaseHas('files', [
            'id'           => $d1->id,
            'mime_type'    => 'directory',
            'project_id'   => $project->id,
            'directory_id' => $project->rootDir->id,
        ]);

        // Ensure that only 1 d1 exists
        $this->assertEquals(1,
            File::where('name', 'd1')
                ->where('project_id', $project->id)
                ->count());
    }

    /** @test */
    public function existing_files_should_have_new_versions()
    {
        $project = ProjectFactory::create();
        $globusUpload = $this->setupGlobusUpload('globus/test1', $project->id, $project->owner_id);

        // There is a file named root.txt. So lets create a fake one, and then upload. At that point we should
        // have two files named root.txt. Only one is current, and it shouldn't be the one that we just created.
        $originalRootFile = ProjectFactory::createFakeFile($project, $project->rootDir, "root.txt");

        $globusApiMock = GlobusMockUtils::createGlobusApiMock();
        $importGlobusUploadIntoProjectAction = new ImportGlobusUploadIntoProjectAction($globusUpload, 10,
            $globusApiMock);
        $importGlobusUploadIntoProjectAction();

        // Check if root.txt has a new version and if it is correct
        $this->assertEquals(2,
            File::where('name', 'root.txt')
                ->where('project_id', $project->id)
                ->count());
        $originalRootFile->refresh();
        $this->assertFalse($originalRootFile->current);
        $newRootFile = File::where('name', 'root.txt')
                           ->where('id', '<>', $originalRootFile->id)
                           ->first();
        $this->assertNotNull($newRootFile);
        $this->assertTrue($newRootFile->current);
    }

    /** @test */
    public function uploading_a_file_with_same_name_as_directory_should_skip_processing_the_file()
    {
        $this->fail("bug not fixed - waiting for future work on Globus");
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
    public function uploading_a_directory_with_the_same_name_as_a_file_should_skip_processing_the_directory()
    {
        $this->fail("bug not fixed - waiting for future work on Globus");
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
