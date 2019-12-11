<?php

namespace App\Actions\Globus;

use App\Http\Controllers\Web\Projects\Globus\GlobusUrl;
use App\Models\GlobusUpload;
use App\Models\Project;
use App\Models\User;

class CreateGlobusUploadRequestAction
{
    private $globusApi;
    private $endpointId;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
        $this->endpointId = env('MC_GLOBUS_ENDPOINT_ID');
    }

    public function __invoke(Project $project, User $user, $uuid)
    {
        $path = storage_path("app/__globus_uploads/{$uuid}");
        $globusPath = "/__globus_uploads/{$uuid}/";

        if (!is_dir($path)) {
            echo "mkdir {$path}\n";
            mkdir($path, 0777, true);
        }

        $globusUserId = $this->getGlobusIdentity($user->globus_user);
        $aclId = $this->setAclOnPath($globusPath, $globusUserId);
        GlobusUpload::create([
            'uuid'               => $uuid,
            'path'               => $path,
            'globus_path'        => $globusPath,
            'owner_id'           => $user->id,
            'project_id'         => $project->id,
            'loading'            => false,
            'uploading'          => true,
            'globus_acl_id'      => $aclId,
            'globus_endpoint_id' => $this->endpointId,
            'globus_identity_id' => $globusUserId,
        ]);

        $globusUrl = GlobusUrl::globusUploadUrl($this->endpointId, $globusPath);
        return new GlobusUploadResponse($globusUrl, $this->endpointId, $globusPath);
    }

    private function getGlobusIdentity($globusEmail)
    {
        $resp = $this->globusApi->getIdentities([$globusEmail]);
        return $resp["identities"][0]["id"];
    }

    private function setAclOnPath($globusPath, $globusUserId)
    {
        $endpointAclRule = new EndpointAclRule($globusUserId, $globusPath, "rw", $this->endpointId);
        $resp = $this->globusApi->addEndpointAclRule($endpointAclRule);
        return $resp["access_id"];
    }
}