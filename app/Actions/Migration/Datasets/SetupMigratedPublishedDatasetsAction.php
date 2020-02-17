<?php

namespace App\Actions\Migration\Datasets;

use App\Actions\Globus\EndpointAclRule;
use App\Actions\Globus\GlobusApi;
use App\Models\Dataset;
use Illuminate\Support\Facades\Storage;

class SetupMigratedPublishedDatasetsAction
{
    private $globusApi;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
    }

    public function __invoke()
    {
        $publishedDatasets = Dataset::whereNotNull('published_at')->get();
        $publishedDatasets->each(function (Dataset $dataset) {
            foreach (Storage::disk('mcfs')->allFiles($dataset->zipfileDirPartial()) as $zipfile) {
                $this->linkExistingDatasetZipfileToNewName($zipfile, $dataset);
                $this->setupGlobusAccessForDataset($dataset);
            }
        });
    }

    private function linkExistingDatasetZipfileToNewName($zipfile, Dataset $dataset)
    {
        $existingZipfilePath = Storage::disk('mcfs')->path($zipfile);
        if (Storage::disk('mcfs')->exists($dataset->zipfilePathPartial())) {
            // No need to link old zipfile to new zipfile
            return;
        }
        $newZipfilePath = $dataset->zipfilePath();
        try {
            if (!link($existingZipfilePath, $newZipfilePath)) {
                echo "Unable to link {$existingZipfilePath} to {$newZipfilePath}\n";
            }
        } catch (\Exception $e) {
            echo "Unable to link {$existingZipfilePath} to {$newZipfilePath}\n";
            $exceptionMessage = $e->getMessage();
            echo "  Reason: {$exceptionMessage}\n";
        }
    }

    private function setupGlobusAccessForDataset($dataset)
    {
        $endpointId = config('globus.endpoint');
        try {
            $globusPath = "/".$dataset->publishedGlobusPathPartial()."/";
            $endpointAclRule = new EndpointAclRule("", $globusPath, "r", $endpointId,
                EndpointAclRule::ACLPrincipalTypeAllAuthenticatedUsers);
            $resp = $this->globusApi->addEndpointAclRule($endpointAclRule);
            $aclId = $resp["access_id"];
            $dataset->update([
                'globus_acl_id'      => $aclId,
                'globus_endpoint_id' => $endpointId,
                'globus_path'        => $globusPath,
            ]);
        } catch (\Exception $e) {
            echo "Unable to setup globus for dataset {$dataset->name}/{$dataset->uuid}\n";
            $exceptionMessage = $e->getMessage();
            echo "  Reason: {$exceptionMessage}\n";
        }
    }
}