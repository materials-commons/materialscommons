<?php

namespace App\Actions\Datasets;

use App\Actions\Globus\EndpointAclRule;
use App\Actions\Globus\GlobusApi;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Traits\GetProjectFiles;
use App\Traits\PathForFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateDatasetInGlobusAction
{
    use PathForFile;
    use GetProjectFiles;

    private $globusApi;
    private $endpointId;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
        $this->endpointId = config('globus.endpoint');
    }

    public function __invoke(Dataset $dataset, $isPrivate)
    {
        umask(0);
        $datasetDir = $this->getDatasetDir($dataset, $isPrivate);
        if (!file_exists($datasetDir)) {
            mkdir($datasetDir, 0700, true);
        }

        $datasetFileSelection = new DatasetFileSelection($dataset->file_selection);
        foreach ($this->getCurrentFilesCursorForProject($dataset->project_id) as $file) {
            if ($this->isIncludedFile($datasetFileSelection, $file)) {
                $dirPath = "{$datasetDir}{$file->directory->path}";
                if (!file_exists($dirPath)) {
                    mkdir($dirPath, 0777, true);
                }

                $uuidPath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
                $filePath = "{$datasetDir}{$file->directory->path}/{$file->name}";
                try {
                    if (!link($uuidPath, $filePath)) {
                        Log::error("Unable to link {$uuidPath} to {$filePath}");
                        echo "Unable to link {$uuidPath} to {$filePath}\n";
                    }
                } catch (\Exception $e) {
                    Log::error("Unable to link {$uuidPath} to {$filePath}");
                    $msg = $e->getMessage();
                    echo "Unable to link {$uuidPath} to {$filePath}: {$msg}\n";
                }
            }
        }

        $this->setAcl($dataset, $isPrivate);
    }

    private function getDatasetDir(Dataset $dataset, $isPrivate)
    {
        if ($isPrivate) {
            return Storage::disk('mcfs')->path("__globus_private_datasets/{$dataset->uuid}");
        }

        return Storage::disk('mcfs')->path("__published_datasets/{$dataset->uuid}");
    }

    private function isIncludedFile(DatasetFileSelection $datasetFileSelection, File $file)
    {
        if (!$this->isFileEntry($file)) {
            return false;
        }

        $filePath = "{$file->directory->path}/{$file->name}";
        return $datasetFileSelection->isIncludedFile($filePath);
    }

    private function isFileEntry(File $file)
    {
        return $file->mime_type !== 'directory';
    }

    private function setAcl(Dataset $dataset, $isPrivate)
    {
        if ($isPrivate) {
            $this->setPrivateDatasetAcl($dataset);
        } else {
            $this->setPublishedDatasetAcl($dataset);
        }
    }

    private function setPrivateDatasetAcl(Dataset $dataset)
    {
        $project = Project::with('users')->findOrFail($dataset->project_id);
        $globusPath = "/__globus_private_datasets/{$dataset->uuid}/";
        foreach ($project->users as $user) {
            if (isset($user->globus_user)) {
                $globusUserId = $this->getGlobusIdentity($user->globus_user);
                $endpointAclRule = new EndpointAclRule($globusUserId, $globusPath, "r", $this->endpointId);
                $this->globusApi->addEndpointAclRule($endpointAclRule);
            }
        }
    }

    private function getGlobusIdentity($globusEmail)
    {
        $resp = $this->globusApi->getIdentities([$globusEmail]);
        return $resp["identities"][0]["id"];
    }

    private function setPublishedDatasetAcl(Dataset $dataset)
    {
        $globusPath = "/".$dataset->publishedGlobusPathPartial()."/";
        $endpointAclRule = new EndpointAclRule("", $globusPath, "r", $this->endpointId,
            EndpointAclRule::ACLPrincipalTypeAllAuthenticatedUsers);
        $aclId = $this->globusApi->addEndpointAclRule($endpointAclRule);
        $dataset->update([
            'globus_acl_id'      => $aclId,
            'globus_endpoint_id' => $this->endpointId,
            'globus_path'        => $globusPath,
        ]);
    }
}
