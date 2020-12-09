<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Published\SearchedPublishedDataAuthorsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use Illuminate\Http\Request;

class IndexPublishedDatasetsForAuthorApiController extends Controller
{
    public function __invoke(Request $request, SearchedPublishedDataAuthorsAction $searchedPublishedDataAuthorsAction)
    {
        $search = $request->input('author');
        $datasets = $searchedPublishedDataAuthorsAction($search);
        return DatasetResource::collection($datasets);
    }
}
