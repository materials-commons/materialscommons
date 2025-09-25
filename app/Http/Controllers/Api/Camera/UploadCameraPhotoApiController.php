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

        $dir = $this->getOrCreateSingleDirectoryIfDoesNotExist($project->rootDir, "/CameraUploads", $project, $user->id);
        $f = $validated['file'];
        // Get original filename and extension
        $originalName = pathinfo($f->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $f->getClientOriginalExtension();

        // Append timestamp to filename
        $timestamp = now()->format('Y-m-d_H-i-s');
        $uniqueName = $originalName . '_' . $timestamp . '.' . $extension;

        $file = $createFileAction($project, $dir, '', $validated['file'], 'web', $uniqueName);
        return new FileResource($file);
    }
}
