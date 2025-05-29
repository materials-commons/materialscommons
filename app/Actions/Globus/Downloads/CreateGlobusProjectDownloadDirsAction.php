<?php

namespace App\Actions\Globus\Downloads;

use App\Actions\Globus\EndpointAclRule;
use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\GlobusUrl;
use App\Enums\GlobusStatus;
use App\Models\File;
use App\Models\GlobusUploadDownload;
use App\Traits\Folders\CreateFolder;
use App\Traits\PathForFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateGlobusProjectDownloadDirsAction
{
    use PathForFile;
    use CreateFolder;

    private $globusApi;
    private $endpointId;

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
                       ->where('current', true)
                       ->whereNull('deleted_at')
                       ->whereNull('dataset_id')
                       ->orderBy('path')
                       ->cursor();

        $baseDir = Storage::disk('mcfs')->path("__globus_downloads/{$globusDownload->uuid}");
        $globusPath = "/__globus_downloads/{$globusDownload->uuid}/";

//        $dirsToCreate = $this->determineMinimumSetOfDirsToCreate($allDirs);
//        $this->createDirs($dirsToCreate, $baseDir);

        foreach ($allDirs as $dir) {
            $this->createDirOnDiskIfNotExists("__globus_downloads/{$globusDownload->uuid}{$dir->path}");
            $files = File::where('directory_id', $dir->id)
                         ->whereNull('deleted_at')
                         ->whereNull('dataset_id')
                         ->where('current', true)
                         ->whereNull('path')
                         ->cursor();
            foreach ($files as $file) {
                $uuidPath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
                $filePath = "{$baseDir}{$dir->path}/{$file->name}";
                try {
                    $dirPathForFilePartial = "__globus_downloads/{$globusDownload->uuid}{$dir->path}";
                    $this->createDirOnDiskIfNotExists($dirPathForFilePartial);
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

        // Because users may login using Google rather than their institutional account, and the way
        // that this is represented in Globus is different, we are going to attempt to set 2 ACLs,
        // the first for the given account for globus above, and a second that looks like:
        // "{$user->globus_user}@accounts.google.com" (for example example@exampleu.edu@accounts.google.com)
        $globusUserId2 = $this->getGlobusIdentity("{$user->globus_user}@accounts.google.com");
        $this->setAclOnPath($globusPath, $globusUserId2);

        return $globusDownload->fresh();
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
