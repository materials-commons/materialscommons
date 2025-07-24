<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Jobs\Folders\CreateFolderZipJob;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreateFolderZipWebController extends Controller
{
    public function __invoke(Request $request, $projectId, $folderId)
    {
        $project = Project::findOrFail($projectId);
        $folder = File::projectDirectory($projectId)
                      ->where('id', $folderId)
                      ->firstOrFail();

        // Generate a unique ID for this zip operation
        $zipId = Str::uuid()->toString();

        // Dispatch the job to create the zip file
        CreateFolderZipJob::dispatch($projectId, $folderId, auth()->id(), $zipId);

        return view('projects.folders.create-zip', [
            'project' => $project,
            'folder'  => $folder,
            'zipId'   => $zipId,
        ]);
    }
}