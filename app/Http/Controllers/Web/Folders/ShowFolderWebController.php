<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\ViewModels\Folders\ShowFolderViewModel;

class ShowFolderWebController extends Controller
{
    public function __invoke(Project $project, $folderId)
    {
        $dir = File::where('project_id', $project->id)->where('id', $folderId)->first();
        $viewModel = new ShowFolderViewModel($project, $dir);
        return view('app.projects.folders.show', $viewModel);
    }

    private function showRootFolder(Project $project)
    {
        $directory = File::where('project_id', $project->id)->where('name', '/')->first();
        $viewModel = new ShowFolderViewModel($project, $directory);
        return view('app.projects.folders.show', $viewModel);
    }
}
