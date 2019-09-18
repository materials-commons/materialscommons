<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class ShowFileEntitiesWebController extends Controller
{
    public function __invoke(Project $project, File $file)
    {
        return view('app.files.show', compact('project', 'file'));
    }
}
