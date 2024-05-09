<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Files\CreateFilesAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class UploadFilesWebController extends Controller
{
    public function __invoke(Request $request, CreateFilesAction $createFilesAction, Project $project, $directoryId)
    {
        $validated = $request->validate([
            'files.*' => 'required|file',
        ]);

        $dir = File::findOrFail($directoryId);
        $createFilesAction($project, $dir, $validated['files']);
    }
}
