<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Jobs\Folders\CreateFolderZipJob;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreateFolderZipWebController extends Controller
{
    public function __invoke(Request $request, $projectId, $folderId)
    {
        $project = Project::findOrFail($projectId);
        $folder = $project->folders()->findOrFail($folderId);
        
        // Authorize the user to access this folder
        $this->authorize('view', [$folder, $project]);
        
        // Generate a unique ID for this zip operation
        $zipId = Str::uuid()->toString();
        
        // Dispatch the job to create the zip file
        CreateFolderZipJob::dispatch($projectId, $folderId, auth()->id(), $zipId);
        
        return view('projects.folders.create-zip', [
            'project' => $project,
            'folder' => $folder,
            'zipId' => $zipId,
        ]);
    }
}