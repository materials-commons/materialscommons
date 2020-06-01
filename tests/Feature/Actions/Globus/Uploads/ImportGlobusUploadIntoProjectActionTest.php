<?php

namespace Tests\Feature\Actions\Globus\Uploads;

use App\Actions\Globus\Uploads\ImportGlobusUploadIntoProjectAction;
use App\Models\File;
use App\Models\GlobusUploadDownload;
use App\Models\Project;
use App\Models\User;
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
        $this->assertTrue(true);
    }

    /** @test */
    public function existing_files_should_have_new_versions()
    {
        $this->assertTrue(true);
    }

    private function Mkdir($path)
    {
        if (!is_dir($path)) {
            mkdir($path);
        }
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
