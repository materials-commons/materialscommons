<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Published\SearchPublishedDataAuthorsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datasets\DatasetResource;
use Illuminate\Http\Request;

class IndexPublishedDatasetsForAuthorApiController extends Controller
{
    public function __invoke(Request $request, SearchPublishedDataAuthorsAction $searchedPublishedDataAuthorsAction)
    {
        $search = $request->input('author');
        $datasets = $searchedPublishedDataAuthorsAction($search);
        return DatasetResource::collection($datasets);
    }
}
