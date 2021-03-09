<?php

namespace App\Actions\Globus;

use App\Models\GlobusTransfer;
use App\Models\TransferRequest;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
        $globusTransfer = GlobusTransfer::where('project_id', $projectId)
                                        ->where('owner_id', $user->id)
                                        ->where('state', 'open')
                                        ->first();
        if (!is_null($globusTransfer)) {
            return $globusTransfer;
        }

        return $this->createGlobusTransfer($projectId, $user);
    }

    private function createGlobusTransfer($projectId, User $user)
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

        $globusTransfer->refresh();

        return $globusTransfer;
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
        Http::post("http://localhost:1323/api/start-bridge", [
            "transfer_request_id" => $transferRequest->id,
            "mount_path"          => $mountPath,
            "log_path"            => $logPath,
        ]);
    }
}