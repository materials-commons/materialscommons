<?php

namespace App\Actions\Globus\Downloads;

use App\Actions\Globus\EndpointAclRule;
use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\GlobusUrl;
use App\Enums\GlobusStatus;
use App\Models\File;
use App\Models\GlobusUploadDownload;
use App\Traits\PathForFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateGlobusProjectDownloadDirsAction
{
    use PathForFile;

    private $globusApi;
    private $endpointId;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
        $this->endpointId = config('globus.endpoint');
    }

    public function __invoke(GlobusUploadDownload $globusDownload, $user)
    {
        umask(0);
        $globusDownload->update(['status' => GlobusStatus::Loading]);
        $allDirs = File::where('project_id', $globusDownload->project_id)
                       ->where('mime_type', 'directory')
                       ->orderBy('path')
                       ->get();

        $baseDir = Storage::disk('mcfs')->path("__globus_downloads/{$globusDownload->uuid}");
        $globusPath = "/__globus_downloads/{$globusDownload->uuid}/";

        $dirsToCreate = $this->determineMinimumSetOfDirsToCreate($allDirs);
        $this->createDirs($dirsToCreate, $baseDir);

        foreach ($allDirs as $dir) {
            $files = File::where('directory_id', $dir->id)->whereNull('path')->get();
            foreach ($files as $file) {
                $uuidPath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
                $filePath = "{$baseDir}{$dir->path}/{$file->name}";
                try {
                    if ( ! link($uuidPath, $filePath)) {
                        echo "Unable to link {$uuidPath} to {$filePath}\n";
                        Log::error("Unable to link {$uuidPath} to {$filePath}");
                    }
                } catch (\Exception $e) {
                    echo "Unable to link {$uuidPath} to {$filePath}\n";
                    Log::error("Unable to link {$uuidPath} to {$filePath}");
                }
            }
        }

        $globusIdentity = $this->getGlobusIdentity($user->globus_user);
        $aclId = $this->setAclOnPath($globusPath, $globusIdentity);
        $globusDownload->update([
            'globus_acl_id'      => $aclId,
            'globus_endpoint_id' => $this->endpointId,
            'globus_identity_id' => $globusIdentity,
            'globus_url'         => GlobusUrl::globusDownloadUrl($this->endpointId, $globusPath),
            'globus_path'        => $globusPath,
            'status'             => GlobusStatus::Done,
            'path'               => $baseDir,
        ]);
        return $globusDownload->fresh();
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
        $endpointAclRule = new EndpointAclRule($globusUserId, $globusPath, "r", $this->endpointId);
        $resp = $this->globusApi->addEndpointAclRule($endpointAclRule);
        return $resp["access_id"];
    }

    private function getGlobusIdentity($globusEmail)
    {
        $resp = $this->globusApi->getIdentities([$globusEmail]);
        return $resp["identities"][0]["id"];
    }
}
