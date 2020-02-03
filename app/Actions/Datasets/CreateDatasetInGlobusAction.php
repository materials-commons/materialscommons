<?php

namespace App\Actions\Datasets;

use App\Actions\Globus\EndpointAclRule;
use App\Actions\Globus\GlobusApi;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Traits\PathFromUUID;
use Illuminate\Support\Facades\Log;

class CreateDatasetInGlobusAction
{
    use PathFromUUID;

    private $globusApi;
    private $endpointId;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
        $this->endpointId = env('MC_GLOBUS_ENDPOINT_ID');
    }

    public function __invoke($datasetId, $isPrivate)
    {
        $dataset = Dataset::find($datasetId);
        $datasetDir = $this->getDatasetDir($dataset, $isPrivate);
        if (!file_exists($datasetDir)) {
            mkdir($datasetDir, 0700, true);
        }

        $datasetFileSelection = new DatasetFileSelection($dataset->file_selection);
        foreach (File::with('directory')->where('project_id', $dataset->project_id)->cursor() as $file) {
            if ($this->isIncludedFile($datasetFileSelection, $file)) {
                $dirPath = "{$datasetDir}{$file->directory->path}";
                if (!file_exists($dirPath)) {
                    mkdir($dirPath, 0700, true);
                }

                $uuid = $file->uses_uuid ?? $file->uuid;
                $uuidPath = $this->filePathFromUuid($uuid);
                $filePath = "{$datasetDir}{$file->directory->path}/{$file->name}";
                try {
                    link($uuidPath, $filePath);
                } catch (\Exception $e) {
                    Log::error("Unable to link {$uuidPath} to {$filePath}");
                }
            }
        }

        $this->setAcl($dataset, $isPrivate);
    }

    private function getDatasetDir(Dataset $dataset, $isPrivate)
    {
        if ($isPrivate) {
            return storage_path("app/__globus_private_datasets/{$dataset->uuid}");
        }

        return storage_path("app/__globus_published_datasets/{$dataset->uuid}");
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
        $globusPath = "/__globus_published_datasets/{$dataset->uuid}/";
        $endpointAclRule = new EndpointAclRule("", $globusPath, "r", $this->endpointId,
            EndpointAclRule::ACLPrincipalTypeAllAuthenticatedUsers);
        $this->globusApi->addEndpointAclRule($endpointAclRule);
    }
}