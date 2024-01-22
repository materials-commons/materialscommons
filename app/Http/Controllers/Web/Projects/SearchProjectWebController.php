<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Projects\SearchProjectAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use function trim;

class SearchProjectWebController extends Controller
{
    public function __invoke(Request $request, SearchProjectAction $searchProjectAction, Project $project)
    {
        $search = trim($request->input('search'));
        if ($search == "") {
            return "";
        }
        $searchResults = $searchProjectAction($search, $project->id);
        return view('partials._htmx_search', [
            'searchResults' => $searchResults,
            'search'        => $search,
            'searchRoute'   => route('projects.search.htmx', [$project, 'search' => '']),
        ]);
    }
}
