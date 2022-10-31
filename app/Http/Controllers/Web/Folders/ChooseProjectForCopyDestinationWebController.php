<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;
use function auth;

class ChooseProjectForCopyDestinationWebController extends Controller
{
    use UserProjects;

    public function __invoke(Request $request, Project $project, File $file, $copyType)
    {
        // Get all the users projects except for the current project. For this view the current project
        // won't show up in the list of projects. Instead, the user will be able to easily select the
        // current projects as the destination for the copy in the view, and only refer to the list
        // of projects if they want a different project to be used.
        $projects = $this->getUserProjects(auth()->id())->filter(function(Project $proj) use ($project) {
            return $proj->id != $project->id;
        });

        return view('app.projects.folders.choose-project', [
            'projects' => $projects,
            'project'  => $project,
            'file'     => $file,
            'copyType' => $copyType,
        ]);
    }
}
