<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\PublishDatasetAction2;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;
use Carbon\Carbon;

class PublishDatasetApiController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        $dataset->update(['published_at' => Carbon::now()]);

        $publishDatasetAction = new PublishDatasetAction2();
        $publishDatasetAction->execute($dataset);

        return new DatasetResource($dataset->refresh());
    }
}
