<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Datasets\PublishDatasetAction2;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;

class PublishDatasetApiController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        if (blank($dataset->description)) {
            return response()->json(['error' => 'Dataset must have a description'], 403);
        }

        if (!$dataset->hasSelectedFiles()) {
            return response()->json(['error' => 'Dataset has no files'], 403);
        }

        if (!is_null($dataset->cleanup_started_at)) {
            return response()->json(['error' => 'Dataset is being unpublished'], 403);
        }

        $publishDatasetAction = new PublishDatasetAction2();
        $publishAs = 'public';
        if (request()->has('test')) {
            $publishAs = 'test';
        }
        $publishDatasetAction->execute($dataset, auth()->user(), $publishAs);

        return new DatasetResource($dataset->refresh());
    }
}
