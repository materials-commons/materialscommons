<?php

namespace App\Actions\Globus;

use App\Models\GlobusTransfer;
use App\Models\TransferRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class OpenGlobusTransferAction
{
    private GlobusApi $globusApi;
    private $endpointId;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
        $this->endpointId = config('globus.endpoint');
    }

    public function execute($projectId, User $user)
    {
        $transferRequest = TransferRequest::create([
            'project_id' => $projectId,
            'owner_id'   => $user->id,
            'state'      => 'open',
        ]);

        $globusTransfer = GlobusTransfer::create([
            'project_id'          => $projectId,
            'owner_id'            => $user->id,
            'state'               => 'open',
            'transfer_request_id' => $transferRequest->id,
        ]);

        $mountPath = Storage::disk('mcfs')->path("__transfers/{$transferRequest->uuid}");
        $transferPath = "/__transfers/{$transferRequest->uuid}/";

        if (!is_dir($mountPath)) {
            $old = umask(0);
            mkdir($mountPath, 0777, true);
            umask($old);
        }

        $globusUserId = $this->getGlobusIdentity($user->globus_user);
        $aclId = $this->setAclOnPath($transferPath, $globusUserId);
        $globusTransfer->update([
            'globus_acl_id'      => $aclId,
            'globus_endpoint_id' => $this->endpointId,
            'globus_identity_id' => $globusUserId,
            'globus_url'         => GlobusUrl::globusUploadUrl($this->endpointId, $transferPath),
            'globus_path'        => $transferPath,
            'path'               => $mountPath,
        ]);

        $this->startMCBridgeFS($transferRequest, $mountPath);

        return $transferRequest->fresh();
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

    private function startMCBridgeFS(TransferRequest $transferRequest, $mountPath)
    {
        Storage::disk('mcfs')->makeDirectory('bridge_logs');
        $logPath = Storage::disk('mcfs')->path("bridge_logs/{$transferRequest->uuid}.log");
        $command = "nohup /usr/local/bin/mcbridgefs.sh {$transferRequest->id} {$mountPath} > {$logPath} 2>&1&";
        $process = Process::fromShellCommandline($command);
        $process->start();
    }
}