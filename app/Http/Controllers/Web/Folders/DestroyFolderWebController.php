<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Directories\DeleteDirectoryAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\Folders\DestinationProject;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DestroyFolderWebController extends Controller
{
    use DestinationProject;

    public function __invoke(Request $request, DeleteDirectoryAction $deleteDirectoryAction, Project $project, $dirId)
    {
        $arg = $request->get('arg');
        $destinationProject = $this->getDestinationProjectId($project);
        $dir = File::with('directory')->findOrFail($dirId);
        $parent = $dir->directory;
        $dir->update(['deleted_at' => Carbon::now()]);
        return redirect(route('projects.folders.show',
            [$project, $parent, 'destproject' => $destinationProject, 'arg' => $arg]));
    }
}
