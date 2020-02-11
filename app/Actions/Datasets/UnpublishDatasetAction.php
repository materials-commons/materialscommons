<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use Illuminate\Support\Facades\Storage;

class UnpublishDatasetAction
{
    public function __invoke(Dataset $dataset)
    {
        $dataset->update(['published_at' => null]);
        Storage::disk('mcfs')->deleteDirectory($dataset->publishedGlobusPathPartial());
        Storage::disk('mcfs')->deleteDirectory($dataset->zipfileDirPartial());

        return $dataset->fresh();
    }
}