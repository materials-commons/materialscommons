<?php

namespace App\Http\Controllers\Web\Files\Trashcan;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class RestoreDirectoryFromTrashcanWebController extends Controller
{
    public function __invoke(Request $request, Project $project, File $dir)
    {
        if ($dir->project_id !== $project->id) {
            flash("Directory {$dir->path} not in project: {$project->name}.")->error();
            return redirect(route('projects.trashcan.index', [$project]));
        }

        // find out if there is already a directory with the same name in the project.
        $existingDir = File::where('project_id', $project->id)
                           ->active()
                           ->directories()
                           ->where('directory_id', $dir->directory_id)
                           ->where('name', $dir->name)
                           ->first();
        if (!is_null($existingDir)) {
            flash("Directory {$dir->path} already exists in project: {$project->name}. Rename or delete the existing first.")->error();
            return redirect(route('projects.trashcan.index', [$project]));
        }

        $dir->update(['deleted_at' => null]);
        flash("Directory {$dir->path} restored.")->success();
        return redirect(route('projects.trashcan.index', [$project]));
    }
}
