<?php

namespace App\Actions\Globus\Downloads;

use App\Actions\Globus\EndpointAclRule;
use App\Actions\Globus\GlobusApi;
use App\Models\File;
use App\Models\User;
use App\Traits\PathFromUUID;
use Exception;
use Illuminate\Support\Str;

class CreateGlobusDownloadForProjectAction
{
    use PathFromUUID;

    private $globusApi;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
    }

    public function __invoke($projectId, User $user)
    {
        $allDirs = File::where('project_id', $projectId)
            ->where('mime_type', 'directory')
            ->orderBy('path')
            ->get();

        $baseDir = storage_path("app/__globus_downloads/{$projectId}/{$user->uuid}");
        $globusPath = "/__globus_downloads/{$projectId}/{$user->uuid}/";
        $globusIdentity = $this->getGlobusIdentity($user->globus_user);
        $aclId = $this->setAclOnPath($globusPath, $globusIdentity);

        $dirsToCreate = $this->determineMinimumSetOfDirsToCreate($allDirs);
        $this->createDirs($dirsToCreate, $baseDir);

        foreach ($allDirs as $dir) {
            $files = File::where('directory_id', $dir->id)->whereNull('path')->get();
            foreach ($files as $file) {
                $uuid = $file->uses_uuid ?? $file->uuid;
                $uuidPath = $this->filePathFromUuid($uuid);
                $filePath = "{$baseDir}{$dir->path}/{$file->name}";
                link($uuidPath, $filePath);
            }
        }
    }

    private function determineMinimumSetOfDirsToCreate($allDirs)
    {
        $dirsToKeep = collect();
        $previousDir = $allDirs[0];
        foreach ($allDirs as $dir) {
            if (Str::startsWith($dir->path, $previousDir->path)) {
                $previousDir = $dir;
            } else {
                $dirsToKeep->put($previousDir->path, true);
                $previousDir = $dir;
            }
        }

        $lastDir = $allDirs->last();
        if (!$dirsToKeep->contains($lastDir->path)) {
            $dirsToKeep->put($lastDir->path, true);
        }

        return $dirsToKeep->keys()->all();
    }

    private function createDirs($dirsToKeep, $basePath)
    {
        foreach ($dirsToKeep as $dir) {
            try {
                $path = "{$basePath}{$dir}";
                mkdir($path, 0777, true);
            } catch (Exception $e) {
                // ignore
            }
        }
    }

    private function setAclOnPath($globusPath, $globusUserId)
    {
        $endpointAclRule = new EndpointAclRule($globusUserId, $globusPath, "r", env('MC_GLOBUS_ENDPOINT_ID'));
        $resp = $this->globusApi->addEndpointAclRule($endpointAclRule);
        return $resp["access_id"];
    }

    private function getGlobusIdentity($globusEmail)
    {
        $resp = $this->globusApi->getIdentities([$globusEmail]);
        return $resp["identities"][0]["id"];
    }
}