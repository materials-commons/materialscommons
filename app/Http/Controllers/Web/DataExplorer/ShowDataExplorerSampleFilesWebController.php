<?php

namespace App\Http\Controllers\Web\DataExplorer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowDataExplorerSampleFilesWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.data-explorer.index', [
            'project' => $project,
        ]);
    }
}
