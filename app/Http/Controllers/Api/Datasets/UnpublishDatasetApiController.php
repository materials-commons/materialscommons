<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\UnpublishDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;

class UnpublishDatasetApiController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        $dataset->update(['published_at' => null]);

        $unpublishDatasetAction = new UnpublishDatasetAction();
        $unpublishDatasetAction($dataset, auth()->user());

        return new DatasetResource($dataset->refresh());
    }
}
