<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class DeleteFileWebController extends Controller
{
    public function __invoke(Project $project, File $file)
    {
        return view('app.projects.files.delete', compact('project', 'file'));
    }
}
