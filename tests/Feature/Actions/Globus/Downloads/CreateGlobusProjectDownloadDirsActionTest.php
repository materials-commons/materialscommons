<?php

namespace Tests\Feature\Actions\Globus\Downloads;

use App\Actions\Globus\Downloads\CreateGlobusDownloadForProjectAction;
use App\Actions\Globus\Downloads\CreateGlobusProjectDownloadDirsAction;
use App\Actions\Globus\GlobusApi;
use App\Enums\GlobusStatus;
use App\Enums\GlobusType;
use App\Models\File;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Utils\StorageUtils;

class CreateGlobusProjectDownloadDirsActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_globus_download_request_should_be_created()
    {
        $globusApiMock = \Mockery::mock(GlobusApi::class);
        $globusApiMock->shouldReceive("getIdentities")->andReturns(["identities" => [["id" => "user_id_abc123"]]]);
        $globusApiMock->shouldReceive("addEndpointAclRule")->andReturns(["access_id" => "acl_id_1234"]);

        $project = ProjectFactory::create();
        ProjectFactory::createFile($project, $project->rootDir, "test.txt", "test");

        $rootDir = $project->rootDir;
        $user = $project->owner;

        File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'dir1',
            'path'         => '/dir1',
            'mime_type'    => 'directory',
            'directory_id' => $rootDir->id,
            'owner_id'     => $project->owner_id,
        ]);

        $downloadData = [];
        $downloadData['name'] = 'globus download';

        $createGlobusDownloadForProjectAction = new CreateGlobusDownloadForProjectAction();
        $globusDownload = $createGlobusDownloadForProjectAction($downloadData, $project->id, $user);

        $createGlobusProjectDownloadDirsAction = new CreateGlobusProjectDownloadDirsAction($globusApiMock);
        $globusDownload = $createGlobusProjectDownloadDirsAction($globusDownload, $user);
        $endpointId = config('globus.endpoint');
        $this->assertEquals($globusDownload->globus_endpoint_id, $endpointId);

        $expectedGlobusPath = "/__globus_downloads/{$globusDownload->uuid}/";
        $this->assertEquals($expectedGlobusPath, $globusDownload->globus_path);

        $expectedUrl = "https://app.globus.org/file-manager?origin_id={$endpointId}&origin_path={$expectedGlobusPath}";
        $this->assertEquals($expectedUrl, $globusDownload->globus_url);

        $this->assertDatabaseHas('globus_uploads_downloads', [
            'owner_id'           => $user->id,
            'project_id'         => $project->id,
            'status'             => GlobusStatus::Done,
            'globus_path'        => $expectedGlobusPath,
            'globus_endpoint_id' => $endpointId,
            'globus_identity_id' => 'user_id_abc123',
            'globus_acl_id'      => 'acl_id_1234',
            'type'               => GlobusType::ProjectDownload,
            'path'               => Storage::disk('mcfs')->path("__globus_downloads/{$globusDownload->uuid}"),
        ]);

        Storage::disk('mcfs')->assertExists("__globus_downloads/{$globusDownload->uuid}");
        Storage::disk('mcfs')->assertExists("__globus_downloads/{$globusDownload->uuid}/dir1");
        Storage::disk('mcfs')->assertExists("__globus_downloads/{$globusDownload->uuid}/test.txt");
        StorageUtils::clearStorage();
    }
}
