<?php

namespace App\Actions\Globus\Uploads;

use App\Actions\Globus\EndpointAclRule;
use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\GlobusUrl;
use App\Models\GlobusUploadDownload;
use App\Models\User;

class CreateGlobusUploadAction
{
    private $globusApi;
    private $endpointId;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
        $this->endpointId = env('MC_GLOBUS_ENDPOINT_ID');
    }

    public function __invoke($data, $projectId, User $user)
    {
        $data['project_id'] = $projectId;
        $data['owner_id'] = $user->id;
        $data['loading'] = false;
        $data['uploading'] = true;
        $data['type'] = 'upload';
        $globusUpload = GlobusUploadDownload::create($data);

        $path = storage_path("app/__globus_uploads/{$globusUpload->uuid}");
        $globusPath = "/__globus_uploads/{$globusUpload->uuid}/";

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $globusUserId = $this->getGlobusIdentity($user->globus_user);
        $aclId = $this->setAclOnPath($globusPath, $globusUserId);
        $globusUpload->update([
            'globus_acl_id'      => $aclId,
            'globus_endpoint_id' => $this->endpointId,
            'globus_identity_id' => $globusUserId,
            'globus_url'         => GlobusUrl::globusUploadUrl($this->endpointId, $globusPath),
            'globus_path'        => $globusPath,
            'path'               => $path,
        ]);

        return $globusUpload->fresh();
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