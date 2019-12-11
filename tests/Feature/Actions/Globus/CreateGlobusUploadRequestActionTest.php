<?php

namespace Tests\Feature\Actions\Globus;

use App\Actions\Globus\CreateGlobusUploadRequestAction;
use App\Actions\Globus\GlobusApi;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class CreateGlobusUploadRequestActionTest extends TestCase
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
        $uuid = Uuid::uuid4()->toString();

        $createGlobusUploadRequestAction = new CreateGlobusUploadRequestAction($globusApiMock);
        $resp = $createGlobusUploadRequestAction($project, $user, $uuid);
        $endpointId = env('MC_GLOBUS_ENDPOINT_ID');
        $this->assertEquals($resp->globusEndpointId, $endpointId);
        $expectedGlobusPath = "/__globus_uploads/${uuid}/";
        $expectedUrl = "https://app.globus.org/file-manager?destination_id={$endpointId}&destination_path={$expectedGlobusPath}";
        $this->assertEquals($expectedUrl, $resp->globusUrl);
        $this->assertEquals($expectedGlobusPath, $resp->globusEndpointPath);
        $this->assertDatabaseHas('globus_uploads', [
            'uuid'               => $uuid,
            'owner_id'           => $user->id,
            'project_id'         => $project->id,
            'loading'            => false,
            'uploading'          => true,
            'globus_path'        => $expectedGlobusPath,
            'globus_endpoint_id' => $endpointId,
            'globus_identity_id' => 'user_id_abc123',
            'globus_acl_id'      => 'acl_id_1234',
            'path'               => storage_path("app/__globus_uploads/{$uuid}"),
        ]);
    }
}
