<?php

namespace App\Http\Controllers\Web\Projects\BrowseTree;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowProjectBrowseTreeWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.browse-tree.show', [
            'project' => $project,
        ]);
    }
}
