<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowAddUrlWebController extends Controller
{
    public function __invoke(Request $request, Project $project, File $folder)
    {
        return view('app.projects.folders.add-url', [
            'project' => $project,
            'directory' => $folder,
            'destDir' => $request->query('destdir'),
            'destProj' => $request->query('destproj'),
            'arg' => $request->query('arg'),
        ]);
    }
}
