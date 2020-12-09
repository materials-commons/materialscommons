<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;
use Illuminate\Http\Request;

class IndexPublishedDatasetsForTagApiController extends Controller
{
    public function __invoke(Request $request)
    {
        $tag = $request->input('tag');
        $datasets = Dataset::withAnyTags([$tag])->with('tags')->whereNotNull('published_at')->get();
        return DatasetResource::collection($datasets);
    }
}
