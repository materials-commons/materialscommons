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
        if (!is_null($dataset->publish_started_at)) {
            return response()->json(['error' => "Dataset is still being published"], 403);
        }

        if (is_null($dataset->published_at)) {
            return response()->json(['error' => 'Dataset was not published'], 403);
        }

        $unpublishDatasetAction = new UnpublishDatasetAction();
        $unpublishDatasetAction($dataset, auth()->user());

        return new DatasetResource($dataset->refresh());
    }
}
