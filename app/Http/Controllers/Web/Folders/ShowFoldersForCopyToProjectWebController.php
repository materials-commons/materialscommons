<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\GetProjectFolderFiles;
use App\Traits\Projects\CanAccessProject;
use App\ViewModels\Folders\ShowCopyToFolderViewModel;
use Illuminate\Http\Request;
use function abort_unless;
use function auth;

class ShowFoldersForCopyToProjectWebController extends Controller
{
    use CanAccessProject;
    use GetProjectFolderFiles;

    public function __invoke(Request $request, Project $leftProject, File $leftFolder, Project $rightProject,
                             File    $rightFolder)
    {
        $this->ensureAccess($leftProject, $leftFolder);
        $this->ensureAccess($rightProject, $rightFolder);

        $leftFiles = $this->getProjectFolderFiles($leftProject->id, $leftFolder->id);
        $rightFiles = $this->getProjectFolderFiles($rightProject->id, $rightFolder->id);
        $viewModel = (new ShowCopyToFolderViewModel())
            ->withLeftProject($leftProject)
            ->withLeftDirectory($leftFolder)
            ->withLeftFiles($leftFiles)
            ->withRightProject($rightProject)
            ->withRightDirectory($rightFolder)
            ->withRightFiles($rightFiles);
        return view('app.projects.folders.show-for-copy', $viewModel);
    }

    private function ensureAccess(Project $project, File $folder)
    {
        abort_unless($project->id == $folder->project_id, 404, "No such folder in project");
        abort_unless($this->userCanAccessProject(auth()->id(), $project), 404, "No such project");
    }
}
