<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Models\Script;
use App\Traits\Folders\DestinationProject;
use App\Traits\GetProjectFolderFiles;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function auth;
use function view;

class ShowRootFolderWebController extends Controller
{
    use GetProjectFolderFiles;
    use UserProjects;
    use DestinationProject;

    public function __invoke(Request $request, Project $project)
    {
        $destProj = $this->getDestinationProject($project);
        $destDir = $this->getDestinationDirId();
        $arg = $request->input('arg');

        auth()->user()->addToRecentlyAccessedProjects($project);
        $dir = File::where('project_id', $project->id)->where('name', '/')->first();
        $files = $this->getProjectFolderFiles($project->id, '/');
        $readme = $files->first(function ($file) {
            return Str::lower($file->name) == "readme.md";
        });
        $projects = $this->getUserProjects(auth()->id());
        $dirsInProject = File::where('project_id', $destProj->id)
                             ->where('mime_type', 'directory')
                             ->whereNull('dataset_id')
                             ->whereNull('deleted_at')
                             ->where('current', true)
                             ->where('id', '<>', $dir->id)
                             ->orderBy('path')
                             ->get();

        $scripts = Script::listForProject($project);

        return view('app.projects.folders.show', [
            'project'       => $project,
            'destProj'      => $destProj,
            'readme'        => $readme,
            'scripts'       => $scripts,
            'projects'      => $projects,
            'directory'     => $dir,
            'dirsInProject' => $dirsInProject,
            'files'         => $files,
            'destDir'       => $destDir,
            'arg'           => $arg,
        ]);
    }
}
