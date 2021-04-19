<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use Illuminate\Support\Facades\Storage;

class UnpublishDatasetAction
{
    public function __invoke(Dataset $dataset)
    {
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