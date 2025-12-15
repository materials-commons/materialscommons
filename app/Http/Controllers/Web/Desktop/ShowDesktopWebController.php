<?php

namespace App\Http\Controllers\Web\Desktop;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowDesktopWebController extends Controller
{
    public function __invoke(Project $project, $desktopId)
    {
        return view('app.projects.desktops.show', [
            'project'   => $project,
            'desktopId' => $desktopId
        ]);
    }
}
