<?php

namespace App\Http\Controllers\Web\Desktop;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\MCDesktopAppService;

class ShowDesktopWebController extends Controller
{
    public function __invoke(Project $project, $desktopId, $hostname)
    {
        $dir = request()->get('dir');

        $clientFiles = MCDesktopAppService::getDesktopProjectDirListing($desktopId, $project->id, auth()->id(), $dir);

        return view('app.projects.desktops.show', [
            'project'     => $project,
            'desktopId'   => $desktopId,
            'hostname'    => $hostname,
            'dir'         => $dir,
            'clientFiles' => $clientFiles,
        ]);
    }
}
