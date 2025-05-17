<?php

namespace App\Actions\Globus\Uploads;

use App\Actions\Globus\EndpointAclRule;
use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\GlobusUrl;
use App\Enums\GlobusStatus;
use App\Enums\GlobusType;
use App\Models\File;
use App\Models\GlobusUploadDownload;
use App\Models\User;
use App\Traits\Folders\CreateFolder;
use Illuminate\Support\Facades\Storage;

class CreateGlobusUploadAction
{
    use CreateFolder;

    private $globusApi;
    private $endpointId;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
        $this->endpointId = config('globus.endpoint');
        $this->knownDirectories = [];
    }

    public function __invoke($data, $projectId, User $user)
    {
        $data['project_id'] = $projectId;
        $data['owner_id'] = $user->id;
        $data['type'] = GlobusType::ProjectUpload;
        $data['status'] = GlobusStatus::Uploading;
        $data['errors'] = 0;
        $globusUpload = GlobusUploadDownload::create($data);

        $path = Storage::disk('mcfs')->path("__globus_uploads/{$globusUpload->uuid}");
        $globusPath = "/__globus_uploads/{$globusUpload->uuid}/";

        if (!is_dir($path)) {
            $old = umask(0);
            mkdir($path, 0777, true);
            umask($old);
        }

        $allDirs = File::where('project_id', $projectId)
                       ->where('mime_type', 'directory')
                       ->where('current', true)
                       ->whereNull('deleted_at')
                       ->whereNull('dataset_id')
                       ->orderBy('path')
                       ->cursor();
        foreach ($allDirs as $dir) {
            $this->createDirOnDiskIfNotExists("__globus_downloads/{$globusUpload->uuid}{$dir->path}");
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

        // Because users may login using Google rather than their institutional account, and the way
        // that this is represented in Globus is different, we are going to attempt to set 2 ACLs,
        // the first for the given account for globus above, and a second that looks like:
        // "{$user->globus_user}@accounts.google.com" (for example example@exampleu.edu@accounts.google.com)
        $globusUserId2 = $this->getGlobusIdentity("{$user->globus_user}@accounts.google.com");
        $this->setAclOnPath($globusPath, $globusUserId2);

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
