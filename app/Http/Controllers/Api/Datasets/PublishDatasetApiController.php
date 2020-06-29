<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use App\Jobs\Datasets\PublishDatasetJob;
use App\Models\Dataset;
use Carbon\Carbon;

class PublishDatasetApiController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        $dataset->update(['published_at' => Carbon::now()]);

        $publishDatasetJob = new PublishDatasetJob($dataset->id);
        dispatch($publishDatasetJob)->onQueue('globus');

        return new DatasetResource($dataset->refresh());
    }
}
