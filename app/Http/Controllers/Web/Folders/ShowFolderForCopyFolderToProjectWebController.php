<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\ViewModels\Folders\ShowFolderViewModel;
use Illuminate\Http\Request;

class ShowFolderForCopyFolderToProjectWebController extends Controller
{
    public function __invoke(Request $request, Project $project, $folderId)
    {
        $dir = File::where('project_id', $project->id)
                   ->where('id', $folderId)
                   ->first();
        $files = $this->getProjectFolderFiles($project->id, $folderId);
        $viewModel = (new ShowFolderViewModel($dir, $files))->withProject($project);
        return view('app.projects.folders.show-copy-to', $viewModel);
    }
}
