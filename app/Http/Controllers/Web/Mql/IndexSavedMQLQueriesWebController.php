<?php

namespace App\Http\Controllers\Web\Mql;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SavedQuery;
use Illuminate\Http\Request;

class IndexSavedMQLQueriesWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.mql.index', [
            'project' => $project,
            'queries' => SavedQuery::where('owner_id', auth()->id())
                                   ->where('project_id', $project->id)
                                   ->get(),
        ]);
    }
}
