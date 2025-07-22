<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Traits\GetProjectFiles;
use App\Traits\PathForFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateDatasetInGlobusAction
{
    use PathForFile;
    use GetProjectFiles;

//    private $endpointId;

//    public function __construct()
//    {
//        $this->endpointId = config('globus.endpoint');
//    }

    public function __invoke(Dataset $dataset, $isPrivate = false)
    {
        umask(0);
        $datasetDir = $this->getDatasetDir($dataset, $isPrivate);
        if (!file_exists($datasetDir)) {
            mkdir($datasetDir, 0777, true);
        }

        foreach ($dataset->files()->with('directory')->cursor() as $file) {
            $dirPath = "{$datasetDir}{$file->directory->path}";
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0777, true);
            }

            if ($file->mime_type === 'url') {
                // This file represents a URL. There is nothing to copy over into the directory.
                continue;
            }
            $uuidPath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
            $filePath = "{$datasetDir}{$file->directory->path}/{$file->name}";
            try {
                if (!file_exists($filePath)) {
                    if (!link($uuidPath, $filePath)) {
                        Log::error("Unable to link {$uuidPath} to {$filePath}");
                        echo "Unable to link {$uuidPath} to {$filePath}\n";
                    }
                }
            } catch (\Exception $e) {
                Log::error("Unable to link {$uuidPath} to {$filePath}");
                $msg = $e->getMessage();
                echo "Unable to link {$uuidPath} to {$filePath}: {$msg}\n";
            }
        }

        $dataset->update(['globus_path_exists' => true]);
    }

    private function getDatasetDir(Dataset $dataset, $isPrivate)
    {
        if ($isPrivate) {
            return Storage::disk('mcfs')->path("__globus_private_datasets/{$dataset->uuid}");
        }

        return Storage::disk('mcfs')->path("__published_datasets/{$dataset->uuid}");
    }

//    private function setAcl(Dataset $dataset, $isPrivate)
//    {
//        if ($isPrivate) {
//            $this->setPrivateDatasetAcl($dataset);
//        } else {
//            $this->setPublishedDatasetAcl($dataset);
//        }
//    }
//
//    private function setPrivateDatasetAcl(Dataset $dataset)
//    {
//        $project = Project::with('users')->findOrFail($dataset->project_id);
//        $globusPath = "/__globus_private_datasets/{$dataset->uuid}/";
//        foreach ($project->users as $user) {
//            if (isset($user->globus_user)) {
//                $globusUserId = $this->getGlobusIdentity($user->globus_user);
//                $endpointAclRule = new EndpointAclRule($globusUserId, $globusPath, "r", $this->endpointId);
//                $this->globusApi->addEndpointAclRule($endpointAclRule);
//            }
//        }
//    }
//
//    private function getGlobusIdentity($globusEmail)
//    {
//        $resp = $this->globusApi->getIdentities([$globusEmail]);
//        return $resp["identities"][0]["id"];
//    }
//
//    private function setPublishedDatasetAcl(Dataset $dataset)
//    {
//        $globusPath = "/".$dataset->publishedGlobusPathPartial()."/";
//        $endpointAclRule = new EndpointAclRule("", $globusPath, "r", $this->endpointId,
//            EndpointAclRule::ACLPrincipalTypeAllAuthenticatedUsers);
//        $aclId = $this->globusApi->addEndpointAclRule($endpointAclRule);
//        $dataset->update([
//            'globus_acl_id'      => $aclId,
//            'globus_endpoint_id' => $this->endpointId,
//            'globus_path'        => $globusPath,
//        ]);
//    }
}
