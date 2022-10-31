<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\GetProjectFolderFiles;
use App\ViewModels\Folders\ShowCopyToFolderViewModel;
use Illuminate\Http\Request;

class ShowFolderForCopyFolderToProjectWebController extends Controller
{
    use GetProjectFolderFiles;

    public function __invoke(Request $request, Project $project, $originalFolderId, $folderId)
    {
        $dir = File::where('project_id', $project->id)
                   ->where('id', $folderId)
                   ->first();
        $originalFolder = File::findOrFail($originalFolderId);
        $files = $this->getProjectFolderFiles($project->id, $folderId);
        $viewModel = (new ShowCopyToFolderViewModel($originalFolder, $dir, $files))->withProject($project);
        return view('app.projects.folders.show-copy-to', $viewModel);
    }
}
