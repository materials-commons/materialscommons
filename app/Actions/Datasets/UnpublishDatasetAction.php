<?php

namespace App\Actions\Datasets;

use App\Actions\Globus\GlobusApi;
use App\Models\Dataset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UnpublishDatasetAction
{
    private $globusApi;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
    }

    public function __invoke(Dataset $dataset)
    {
        try {
            $this->globusApi->deleteEndpointAclRule($dataset->globus_endpoint_id, $dataset->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        $dataset->update(['published_at' => null, 'zipfile_size' => 0, 'globus_path_exists' => false]);
        $dataset->files()->delete();
        @Storage::disk('mcfs')->deleteDirectory($dataset->publishedGlobusPathPartial());
        @Storage::disk('mcfs')->deleteDirectory($dataset->zipfileDirPartial());

        $this->removeDatasetRelationships($dataset);

        return $dataset->fresh();
    }

    private function removeDatasetRelationships(Dataset $dataset)
    {
        $dataset->entities()->detach();
        $dataset->activities()->detach();
    }
}