<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class ShowFileWebController extends Controller
{
    public function __invoke(Project $project, File $file)
    {
        return view('app.projects.files.show', compact('project', 'file'));
    }
}
