<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Models\Script;
use App\Traits\GetProjectFolderFiles;
use App\Traits\Projects\UserProjects;
use App\ViewModels\Folders\ShowFolderViewModel;
use Illuminate\Support\Str;
use function auth;

class ShowFolderWebController extends Controller
{
    use GetProjectFolderFiles;
    use UserProjects;

    public function __invoke(Project $project, $folderId)
    {
        $dir = File::where('project_id', $project->id)
                   ->where('id', $folderId)
                   ->first();
        $files = $this->getProjectFolderFiles($project->id, $folderId);

        $readme = $files->first(function ($file) {
            return Str::lower($file->name) == "readme.md";
        });

        $projects = $this->getUserProjects(auth()->id());
        $viewModel = (new ShowFolderViewModel($dir, $files))
            ->withProject($project)
            ->withReadme($readme)
            ->withScripts(Script::listForProject($project))
            ->withProjects($projects);
        return view('app.projects.folders.show', $viewModel);
    }
}
