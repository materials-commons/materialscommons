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

class ShowFolderWebController extends Controller
{
    use GetProjectFolderFiles;
    use UserProjects;
    use DestinationProject;

    public function __invoke(Request $request, Project $project, $folderId)
    {
        $destinationProject = $this->getDestinationProject($project);
        $destDir = $this->getDestinationDirId();
        $arg = $request->input('arg');
        $dir = File::where('project_id', $project->id)
                   ->where('id', $folderId)
                   ->first();
        $files = $this->getProjectFolderFiles($project->id, $folderId);

        $readme = $files->first(function ($file) {
            return Str::lower($file->name) == "readme.md";
        });

        $projects = $this->getUserProjects(auth()->id());

        $dirsInProject = File::where('project_id', $destinationProject->id)
                             ->where('mime_type', 'directory')
                             ->whereNull('dataset_id')
                             ->whereNull('deleted_at')
                             ->where('current', true)
                             ->where('id', '<>', $folderId)
            ->orderBy('path')
                             ->get();

        $scripts = Script::listForProject($project);

        return view('app.projects.folders.show', [
            'project'            => $project,
            'destinationProject' => $destinationProject,
            'readme'             => $readme,
            'scripts'            => $scripts,
            'projects'           => $projects,
            'directory'          => $dir,
            'dirsInProject'      => $dirsInProject,
            'files'              => $files,
            'destDir' => $destDir,
            'arg'                => $arg,
        ]);
    }
}
