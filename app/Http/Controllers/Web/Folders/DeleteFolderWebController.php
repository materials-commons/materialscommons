<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\Folders\DestinationProject;
use Illuminate\Http\Request;

class DeleteFolderWebController extends Controller
{
    use DestinationProject;

    public function __invoke(Request $request, Project $project, File $dir)
    {
        $arg = $request->get('arg');
        $destinationProject = $this->getDestinationProjectId($project);
        return view('app.projects.folders.delete', [
            'project'            => $project,
            'dir'                => $dir,
            'arg'                => $arg,
            'destinationProject' => $destinationProject,
        ]);
    }
}
