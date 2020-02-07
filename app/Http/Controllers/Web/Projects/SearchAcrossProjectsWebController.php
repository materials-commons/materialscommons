<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Projects\SearchAcrossProjectsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchAcrossProjectsWebController extends Controller
{

    public function __invoke(Request $request, SearchAcrossProjectsAction $searchAcrossProjectsAction)
    {
        $search = $request->input('search');
        $searchResults = $searchAcrossProjectsAction($search, auth()->user());
        return view('app.projects.search_all', compact('searchResults', 'search'));
    }
}
