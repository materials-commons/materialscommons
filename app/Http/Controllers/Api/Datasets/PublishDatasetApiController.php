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
        if (!is_null($dataset->cleanup_started_at)) {
            return response()->json(['error' => 'Dataset is being unpublished'], 403);
        }

        if (blank($dataset->description)) {
            return response()->json(['error' => 'Dataset must have a description'], 403);
        }

        if (!$dataset->hasSelectedFiles()) {
            return response()->json(['error' => 'Dataset has no files'], 403);
        }

        $dataset->update(['published_at' => Carbon::now()]);

        $publishDatasetAction = new PublishDatasetAction2();
        $publishDatasetAction->execute($dataset, auth()->user());

        return new DatasetResource($dataset->refresh());
    }
}
