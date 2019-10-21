<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use Carbon\Carbon;

class PublishDatasetAction
{
    public function __invoke(Dataset $dataset)
    {
        $dataset->update(['published_at' => Carbon::now()]);

        return $dataset->fresh();
    }
}