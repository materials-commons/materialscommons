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
            'files.*'      => 'nullable|file',
            'file'         => 'nullable|file',
            'relativePath' => 'nullable|string',
        ]);

        ray($request->all());

        ray("relativePath: {$validated['relativePath']}");

        if (true) {
            return;
        }

        $dir = File::findOrFail($directoryId);
        $createFilesAction($project, $dir, $validated['files']);
    }
}
