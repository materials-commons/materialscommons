<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;

class UnpublishDatasetAction
{
    public function __invoke(Dataset $dataset)
    {
        $dataset->update(['published_at' => null]);

        return $dataset->fresh();
    }
}