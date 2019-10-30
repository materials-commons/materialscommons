<?php

namespace App\Actions\Datasets;

use App\Jobs\PublishDatasetJob;
use App\Models\Dataset;
use Carbon\Carbon;

class PublishDatasetAction
{
    public function __invoke(Dataset $dataset)
    {
        $dataset->update(['published_at' => Carbon::now()]);

        $publishDatasetJob = new PublishDatasetJob($dataset->id);
        dispatch($publishDatasetJob);

        return $dataset->fresh();
    }
}