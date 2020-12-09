<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Actions\Published\SearchPublishedDataAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchPublishedDataApiController extends Controller
{
    public function __invoke(Request $request, SearchPublishedDataAction $searchPublishedDataAction)
    {
        $search = $request->input('search');
        return $searchPublishedDataAction($search);
    }
}
