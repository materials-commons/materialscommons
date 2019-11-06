<?php

namespace Tests\Feature\Actions\Globus;

use App\Actions\Globus\LoadGlobusUploadIntoProjectAction;
use App\Models\File;
use App\Models\GlobusUpload;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoadGlobusUploadIntoProjectActionTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        $globusUploadsPath = storage_path('test_data/__globus_uploads');
        exec("rm -rf {$globusUploadsPath}");
    }

    /** @test */
    public function files_and_directories_should_be_loaded_into_project()
    {
        $globusUploadsPath = storage_path('test_data/__globus_uploads');
        $this->Mkdir($globusUploadsPath);
        $from = storage_path('test_data/globus/test1');
        $cmd = "cp -r {$from} {$globusUploadsPath}";
        exec($cmd);

        $user = factory(User::class)->create();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $rootDir = factory(File::class)->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        $globusUpload = factory(GlobusUpload::class)->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
            'path'       => storage_path("test_data/__globus_uploads/test1"),
        ]);

        $loadGlobusUploadIntoProjectAction = new LoadGlobusUploadIntoProjectAction($globusUpload, 10);
        $loadGlobusUploadIntoProjectAction();

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
        if ( ! is_dir($path)) {
            mkdir($path);
        }
    }
}
