<?php

namespace App\Http\Controllers\Api\Directories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Directories\DirectoryResource;
use App\Models\Project;
use App\Traits\CreateDirectories;
use Illuminate\Http\Request;

class CreateDirectoryByPathApiController extends Controller
{
    use CreateDirectories;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|integer',
            'path'       => 'required|string',
        ]);

        $project = Project::with('rootDir')->findOrFail($validated['project_id']);
        $dir = $this->getDirectoryOrCreateIfDoesNotExist($project->rootDir, $validated['path'], $project);
        return new DirectoryResource($dir);
    }
}
