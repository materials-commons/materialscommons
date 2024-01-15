<?php

namespace App\Http\Controllers\Web\Published;

use App\Actions\Published\SearchPublishedDataAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function route;
use function trim;
use function view;

class SearchPublishedDataWebController extends Controller
{
    public function __invoke(Request $request, SearchPublishedDataAction $searchPublishedDataAction)
    {
        $search = trim($request->input('search'));
        if ($search == "") {
            return "";
        }
        $searchResults = $searchPublishedDataAction($search);
        return view('partials._htmx_search', [
            'searchResults' => $searchResults,
            'search'        => $search,
            'searchRoute'   => route('public.search', ['search' => '']),
        ]);
    }
}
