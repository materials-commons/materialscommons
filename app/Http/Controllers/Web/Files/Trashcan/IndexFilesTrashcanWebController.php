<?php

namespace App\Http\Controllers\Web\Files\Trashcan;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexFilesTrashcanWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.trashcan.index', [
            'trash'   => File::getTrashForProject($project->id),
            'project' => $project,
        ]);
    }
}
