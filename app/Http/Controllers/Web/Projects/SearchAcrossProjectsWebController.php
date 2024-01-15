<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Projects\SearchAcrossProjectsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function route;
use function trim;
use function view;

class SearchAcrossProjectsWebController extends Controller
{

    public function __invoke(Request $request, SearchAcrossProjectsAction $searchAcrossProjectsAction)
    {
        $search = trim($request->input('search'));
        if ($search == "") {
            return "";
        }
        $searchResults = $searchAcrossProjectsAction($search, auth()->user());
        return view('partials._htmx_search', [
            'searchResults' => $searchResults,
            'search'        => $search,
            'searchRoute'   => route('projects.search_all', ['search' => '']),
        ]);
    }
}
