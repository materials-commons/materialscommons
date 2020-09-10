<?php

namespace App\Actions\Migration\Datasets;

use App\Actions\Datasets\CreateDatasetInGlobusAction;
use App\Actions\Globus\EndpointAclRule;
use App\Actions\Globus\GlobusApi;
use App\Models\Dataset;
use Illuminate\Support\Facades\Storage;

class SetupMigratedPublishedDatasetsAction
{
    private $globusApi;
    private $createDatsetInGlobusAction;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
        $this->createDatsetInGlobusAction = new CreateDatasetInGlobusAction($globusApi);
    }

    public function __invoke($runZipLinker, $runGlobus)
    {
        $publishedDatasets = Dataset::whereNotNull('published_at')->get();
        $publishedDatasets->each(function (Dataset $dataset) use ($runZipLinker, $runGlobus) {
            try {
                foreach (Storage::disk('mcfs')->allFiles($dataset->zipfileDirPartial()) as $zipfile) {
                    if ($runZipLinker) {
                        $this->linkExistingDatasetZipfileToNewName($zipfile, $dataset);
                    }
                }
            } catch (\Exception $e) {
                echo "  Failed on all files for dataset {$dataset->name}/{$dataset->uuid}\n";
                $exceptionMessage = $e->getMessage();
                echo "    Reason: {$exceptionMessage}\n";
            }
            try {
                if ($runGlobus) {
                    if (Storage::disk('mcfs')->exists($dataset->publishedGlobusPathPartial())) {
                        $this->setupGlobusAccessForDataset($dataset);
                    } else {
                        ($this->createDatsetInGlobusAction)($dataset->id, false);
                    }
                }
            } catch (\Exception $e) {
                echo "  Failed on exists for globus\n";
                $exceptionMessage = $e->getMessage();
                echo "    Reason: {$exceptionMessage}\n";
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
            //            echo "Globus Path = {$globusPath}\n";
            $endpointAclRule = new EndpointAclRule("", $globusPath, "r", $endpointId,
                EndpointAclRule::ACLPrincipalTypeAllAuthenticatedUsers);
            $resp = $this->globusApi->addEndpointAclRule($endpointAclRule);
            if (isset($resp["access_id"])) {
                $aclId = $resp["access_id"];
                $dataset->update([
                    'globus_acl_id'      => $aclId,
                    'globus_endpoint_id' => $endpointId,
                    'globus_path'        => $globusPath,
                ]);
            }
        } catch (\Exception $e) {
            echo "Unable to setup globus for dataset {$dataset->name}/{$dataset->uuid}\n";
            $exceptionMessage = $e->getMessage();
            echo "  Reason: {$exceptionMessage}\n";
        }
    }
}
