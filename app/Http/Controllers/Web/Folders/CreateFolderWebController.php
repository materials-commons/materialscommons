<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class CreateFolderWebController extends Controller
{
    public function __invoke(Project $project, File $folder)
    {
        $directory = $folder; // View calls it a directory - fix this at some point
        return view('app.projects.folders.create', compact('project', 'directory'));
    }
}
