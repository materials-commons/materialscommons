<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class ShowUploadFilesWebController extends Controller
{
    public function __invoke(Project $project, File $directory)
    {
        return view('app.projects.folders.upload', compact('project', 'directory'));
    }
}