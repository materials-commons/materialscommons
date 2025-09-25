<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\CreateFileAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\Project;
use App\Models\ScriptTrigger;
use App\Traits\CreateDirectories;
use Illuminate\Http\Request;

class UploadFileToPathApiController extends Controller
{
    use CreateDirectories;

    public function __invoke(Request $request, Project $project)
    {
        $validated = $request->validate([
            'file' => 'required|file',
            'path' => 'required|string',
        ]);

        $project->load('rootDir');

        $triggers = ScriptTrigger::getProjectTriggers($project);
        $path = $validated['path'];
        $dirPath = dirname($path);
        $fileName = basename($path);
        $dirToUse = $this->getDirectoryOrCreateIfDoesNotExist($project->rootDir, $dirPath, $project);
        $createFileAction = new CreateFileAction($triggers);
        $f = $createFileAction($project, $dirToUse, '', $validated['file'], 'api', $fileName);
        return new FileResource($f);
    }
}
