<?php

namespace App\Http\Controllers\Web\Files\Trashcan;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class RestoreFileFromTrashcanWebController extends Controller
{
    public function __invoke(Request $request, Project $project, File $file)
    {
        $file->load('directory');
        if ($file->project_id !== $project->id) {
            flash("Directory {$file->getFilePath()} not in project: {$project->name}.")->error();
            return redirect(route('projects.trashcan.index', [$project]));
        }

        $file->update(['deleted_at' => null]);
        // Restore all versions
        File::where('directory_id', $file->directory_id)
            ->where('name', $file->name)
            ->whereNull('dataset_id')
            ->where('current', false)
            ->update(['deleted_at' => null]);
        flash("File {$file->getFilePath()} restored.")->success();
        return redirect(route('projects.trashcan.index', [$project]));
    }
}
