<?php

namespace App\Http\Controllers\Api\Camera;

use App\Actions\Files\CreateFileAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\Project;
use App\Traits\CreateDirectories;
use Illuminate\Http\Request;

class UploadCameraPhotoApiController extends Controller
{
    use CreateDirectories;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, CreateFileAction $createFileAction)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'project_id' => 'required|integer',
        ]);

        $project = Project::with('rootDir')->findOrFail($validated['project_id']);
        $user = auth()->user();

        $dir = $this->getOrCreateSingleDirectoryIfDoesNotExist($project->rootDir, "/CameraPhotos", $project, $user->id);
        $file = $createFileAction($project, $dir, '', $validated['file']);
        return new FileResource($file);
    }
}
