<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use App\Jobs\Datasets\UnpublishDatasetJob;
use App\Models\Dataset;

class UnpublishDatasetApiController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        $dataset->update(['published_at' => null]);

        $unpublishDatasetJob = new UnpublishDatasetJob($dataset->id);
        dispatch($unpublishDatasetJob)->onQueue('globus');

        return new DatasetResource($dataset->refresh());
    }
}
