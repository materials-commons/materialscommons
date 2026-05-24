<?php

namespace App\Http\Controllers\Web\Desktop;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\MCDesktopAppService;
use Illuminate\Http\Request;

class SubmitTestUploadForDesktopWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, $clientId)
    {
        MCDesktopAppService::submitTestUpload($clientId);
        return redirect()->route('projects.desktops.show', [$project, $clientId]);
    }
}
