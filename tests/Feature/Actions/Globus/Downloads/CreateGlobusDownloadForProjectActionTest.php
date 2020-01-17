<?php

namespace Tests\Feature\Actions\Globus\Downloads;

use App\Actions\Globus\Downloads\CreateGlobusDownloadForProjectAction;
use App\Actions\Globus\GlobusApi;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateGlobusDownloadForProjectActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_globus_download_request_should_be_created()
    {
        $globusApiMock = \Mockery::mock(GlobusApi::class);
        $globusApiMock->shouldReceive("getIdentities")->andReturns(["identities" => [["id" => "user_id_abc123"]]]);
        $globusApiMock->shouldReceive("addEndpointAclRule")->andReturns(["access_id" => "acl_id_1234"]);

        $user = factory(User::class)->create();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $rootDir = factory(File::class)->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => 'dir1',
            'path'         => '/dir1',
            'mime_type'    => 'directory',
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
        ]);

        $downloadData = [];
        $downloadData['name'] = 'globus download';

        $createGlobusDownloadForProjectAction = new CreateGlobusDownloadForProjectAction($globusApiMock);
        $globusDownload = $createGlobusDownloadForProjectAction($downloadData, $project->id, $user);
        $endpointId = env('MC_GLOBUS_ENDPOINT_ID');
        $this->assertEquals($globusDownload->globus_endpoint_id, $endpointId);

        $expectedGlobusPath = "/__globus_downloads/{$globusDownload->uuid}/";
        $this->assertEquals($expectedGlobusPath, $globusDownload->globus_path);

        $expectedUrl = "https://app.globus.org/file-manager?origin_id={$endpointId}&origin_path={$expectedGlobusPath}";
        $this->assertEquals($expectedUrl, $globusDownload->globus_url);

        $this->assertDatabaseHas('globus_uploads_downloads', [
            'owner_id'           => $user->id,
            'project_id'         => $project->id,
            'loading'            => false,
            'uploading'          => false,
            'globus_path'        => $expectedGlobusPath,
            'globus_endpoint_id' => $endpointId,
            'globus_identity_id' => 'user_id_abc123',
            'globus_acl_id'      => 'acl_id_1234',
            'type'               => 'download',
            'path'               => storage_path("app/__globus_downloads/{$globusDownload->uuid}"),
        ]);

        Storage::disk('local')->assertExists("__globus_downloads/{$globusDownload->uuid}");
        Storage::disk('local')->assertExists("__globus_downloads/{$globusDownload->uuid}/dir1");
        Storage::disk('local')->deleteDirectory("__globus_downloads");
    }
}
