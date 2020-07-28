<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class DeleteFolderWebController extends Controller
{
    public function __invoke(Project $project, File $dir)
    {
        return view('app.projects.folders.delete', compact('project', 'dir'));
    }
}
