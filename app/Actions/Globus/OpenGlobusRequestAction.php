<?php

namespace App\Actions\Globus;

use App\Models\GlobusRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class OpenGlobusRequestAction
{
    private $globusApi;
    private $endpointId;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
        $this->endpointId = config('globus.endpoint');
    }

    public function execute($projectId, User $user)
    {
        $globusRequest = GlobusRequest::create([
            'project_id' => $projectId,
            'owner_id'   => $user->id,
            'state'      => 'new',
        ]);

        $mountPath = Storage::disk('mcfs')->path("__globus_uploads/{$globusRequest->uuid}");
        $globusPath = "/__globus_uploads/{$globusRequest->uuid}/";

        if (!is_dir($mountPath)) {
            $old = umask(0);
            mkdir($mountPath, 0777, true);
            umask($old);
        }

        $globusUserId = $this->getGlobusIdentity($user->globus_user);
        $aclId = $this->setAclOnPath($globusPath, $globusUserId);
        $globusRequest->update([
            'globus_acl_id'      => $aclId,
            'globus_endpoint_id' => $this->endpointId,
            'globus_identity_id' => $globusUserId,
            'globus_url'         => GlobusUrl::globusUploadUrl($this->endpointId, $globusPath),
            'globus_path'        => $globusPath,
            'path'               => $mountPath,
        ]);

        $this->startMCBridgeFS($globusRequest, $mountPath);

        return $globusRequest->fresh();
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

    private function startMCBridgeFS(GlobusRequest $globusRequest, $mountPath)
    {
        Storage::disk('mcfs')->makeDirectory('bridge_logs');
        $logPath = Storage::disk('mcfs')->path("bridge_logs/{$globusRequest->uuid}.log");
        $command = "nohup /usr/local/bin/mcbridgefs.sh {$globusRequest->id} {$mountPath} > {$logPath} 2>&1&";
        $process = Process::fromShellCommandline($command);
        $process->start();
    }
}