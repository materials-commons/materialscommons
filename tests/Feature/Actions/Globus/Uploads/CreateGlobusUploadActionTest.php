<?php

namespace Tests\Feature\Actions\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\Uploads\CreateGlobusUploadAction;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateGlobusUploadActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_globus_request_should_be_created()
    {
        $globusApiMock = \Mockery::mock(GlobusApi::class);
        $globusApiMock->shouldReceive("getIdentities")->andReturns(["identities" => [["id" => "user_id_abc123"]]]);
        $globusApiMock->shouldReceive("addEndpointAclRule")->andReturns(["access_id" => "acl_id_1234"]);

        $user = factory(User::class)->create();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $uploadData = [];
        $uploadData['name'] = 'globus upload';

        $createGlobusUploadAction = new CreateGlobusUploadAction($globusApiMock);
        $globusUpload = $createGlobusUploadAction($uploadData, $project->id, $user);
        $endpointId = env('MC_GLOBUS_ENDPOINT_ID');
        $this->assertEquals($globusUpload->globus_endpoint_id, $endpointId);
        $expectedGlobusPath = "/__globus_uploads/{$globusUpload->uuid}/";
        $expectedUrl = "https://app.globus.org/file-manager?destination_id={$endpointId}&destination_path={$expectedGlobusPath}";
        $this->assertEquals($expectedUrl, $globusUpload->globus_url);
        $this->assertEquals($expectedGlobusPath, $globusUpload->globus_path);
        $this->assertDatabaseHas('globus_uploads_downloads', [
            'owner_id'           => $user->id,
            'project_id'         => $project->id,
            'loading'            => false,
            'uploading'          => true,
            'globus_path'        => $expectedGlobusPath,
            'globus_endpoint_id' => $endpointId,
            'globus_identity_id' => 'user_id_abc123',
            'globus_acl_id'      => 'acl_id_1234',
            'type'               => 'upload',
            'path'               => storage_path("app/__globus_uploads/{$globusUpload->uuid}"),
        ]);

        Storage::disk('local')->assertExists("__globus_uploads/{$globusUpload->uuid}");
        Storage::disk('local')->deleteDirectory("__globus_uploads");
    }
}
