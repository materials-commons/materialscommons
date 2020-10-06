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

class CreateGlobusProjectDownloadDirsAction
{
    use PathForFile;

    private $globusApi;
    private $endpointId;
    private $knownDirectories;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
        $this->endpointId = config('globus.endpoint');
        $this->knownDirectories = [];
    }

    public function __invoke(GlobusUploadDownload $globusDownload, $user)
    {
        umask(0);
        $globusDownload->update(['status' => GlobusStatus::Loading]);
        $allDirs = File::where('project_id', $globusDownload->project_id)
                       ->where('mime_type', 'directory')
                       ->orderBy('path')
                       ->cursor();

        $baseDir = Storage::disk('mcfs')->path("__globus_downloads/{$globusDownload->uuid}");
        $globusPath = "/__globus_downloads/{$globusDownload->uuid}/";

//        $dirsToCreate = $this->determineMinimumSetOfDirsToCreate($allDirs);
//        $this->createDirs($dirsToCreate, $baseDir);

        foreach ($allDirs as $dir) {
            $this->createDirIfNotExists("__globus_downloads/{$globusDownload->uuid}{$dir->path}");
            $files = File::where('directory_id', $dir->id)
                         ->where('current', true)
                         ->whereNull('path')
                         ->cursor();
            foreach ($files as $file) {
                $uuidPath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
                $filePath = "{$baseDir}{$dir->path}/{$file->name}";
                try {
                    $dirPathForFilePartial = "__globus_downloads/{$globusDownload->uuid}{$dir->path}";
                    $this->createDirIfNotExists($dirPathForFilePartial);
                    if (!link($uuidPath, $filePath)) {
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

    private function createDirIfNotExists($dirPath)
    {
        if (array_key_exists($dirPath, $this->knownDirectories)) {
            return;
        }

        $fullPath = Storage::disk('mcfs')->path($dirPath);
        if (Storage::disk('mcfs')->exists($dirPath)) {
            $this->knownDirectories[$dirPath] = true;
            return;
        }

        Storage::disk('mcfs')->makeDirectory($dirPath);
        $p = Storage::disk('mcfs')->path($dirPath);
        chmod($p, 0777);
        $this->knownDirectories[$dirPath] = true;
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
