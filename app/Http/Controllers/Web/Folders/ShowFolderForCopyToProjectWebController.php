<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\GetProjectFolderFiles;
use App\ViewModels\Folders\ShowCopyToFolderViewModel;
use Illuminate\Http\Request;

class ShowFolderForCopyToProjectWebController extends Controller
{
    use GetProjectFolderFiles;

    public function __invoke(Request $request, Project $project, $fromFolderId, $folderId, $copyType)
    {
        $dir = File::where('project_id', $project->id)
                   ->where('id', $folderId)
                   ->first();
        $fromFolder = File::with(['project'])->findOrFail($fromFolderId);
        $files = $this->getProjectFolderFiles($project->id, $folderId);
        $fromFiles = $this->getProjectFolderFiles($fromFolder->project_id, $fromFolder->id);
        $viewModel = (new ShowCopyToFolderViewModel($fromFolder, $dir, $files))
            ->withProject($project)
            ->withCopyType($copyType)
            ->withFromFiles($fromFiles)
            ->withFromProject($fromFolder->project);
        return view('app.projects.folders.show-for-copy', $viewModel);
    }
}
