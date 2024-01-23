<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Etl\GetFileByPathAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowFileByPathWebController extends Controller
{
    public function __invoke(Request $request, GetFileByPathAction $getFileByPathAction, Project $project)
    {
        $path = $request->input("path");
        $file = $getFileByPathAction->execute($project->id, $path);
        return redirect(route('projects.files.show', [$project, $file]));
    }
}
