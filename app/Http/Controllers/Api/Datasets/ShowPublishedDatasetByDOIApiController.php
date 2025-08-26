<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;
use Illuminate\Http\Request;

class ShowPublishedDatasetByDOIApiController extends Controller
{
    public function __invoke(Request $request)
    {
        $doi = $request->input('doi');
        $isTest = $request->has('test');
        $q = Dataset::with(['owner', 'tags'])->withCounts();
        if ($isTest) {
            $q->where('test_doi', $doi)->whereNotNull('test_published_at');
        } else {
            $q->where('doi', $doi)->whereNotNull('published_at');
        }
        $dataset = $q->first();
        return new DatasetResource($dataset);
    }
}
