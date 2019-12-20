<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Projects\SearchProjectAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class SearchProjectWebController extends Controller
{
    public function __invoke(Request $request, SearchProjectAction $searchProjectAction, Project $project)
    {
        $search = $request->input('search');
        $searchResults = $searchProjectAction($search, $project->id);
        return view('app.projects.search', compact('project', 'searchResults', 'search'));
    }
}
