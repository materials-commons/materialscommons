<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class CompareFilesWebController extends Controller
{
    public function __invoke(Project $project, File $file1, File $file2)
    {
        return view('app.files.compare', [
            'project' => $project,
            'file1'   => $file1,
            'file2'   => $file2,
        ]);
    }
}
