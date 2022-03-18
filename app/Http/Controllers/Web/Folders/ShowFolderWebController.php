<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\GetProjectFolderFiles;
use App\ViewModels\Folders\ShowFolderViewModel;

class ShowFolderWebController extends Controller
{
    use GetProjectFolderFiles;

    public function __invoke(Project $project, $folderId)
    {
        $dir = File::where('project_id', $project->id)
                   ->where('id', $folderId)
                   ->first();
        $files = $this->getProjectFolderFiles($project->id, $folderId);
        $viewModel = (new ShowFolderViewModel($dir, $files))->withProject($project);
        return view('app.projects.folders.show', $viewModel);
    }
}
