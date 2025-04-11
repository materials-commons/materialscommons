<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Files\DeleteFileAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\Folders\DestinationProject;
use Illuminate\Http\Request;

class DestroyFileWebController extends Controller
{
    use DestinationProject;

    public function __invoke(Request $request, DeleteFileAction $deleteFileAction, Project $project, $fileId)
    {
        $arg = $request->get('arg');
        $destinationProject = $this->getDestinationProjectId($arg);
        $file = File::with('directory')->findOrFail($fileId);
        $dir = $file->directory;
        $deleteFileAction($file);
        return redirect(route('projects.folders.show',
            [$project, $dir, 'destproj' => $destinationProject, 'arg' => $arg]));
    }
}
