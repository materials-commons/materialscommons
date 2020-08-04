<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\GetProjectFolderFiles;
use App\ViewModels\Folders\ShowFolderViewModel;

class ShowRootFolderWebController extends Controller
{
    use GetProjectFolderFiles;

    public function __invoke(Project $project)
    {
        $directory = File::where('project_id', $project->id)->where('name', '/')->first();
        $files = $this->getProjectFolderFiles($project->id, '/');
        $viewModel = new ShowFolderViewModel($project, $directory, $files);
        return view('app.projects.folders.show', $viewModel);
    }
}
