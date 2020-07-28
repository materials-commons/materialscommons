<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class RenameFolderWebController extends Controller
{
    public function __invoke(Project $project, File $dir)
    {
        return view('app.projects.folders.rename', compact('project', 'dir'));
    }
}
