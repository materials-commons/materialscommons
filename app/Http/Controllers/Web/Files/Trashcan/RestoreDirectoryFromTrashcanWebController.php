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

        $dir->update(['deleted_at' => null]);
        flash("Directory {$dir->path} restored.")->success();
        return redirect(route('projects.trashcan.index', [$project]));
    }
}
