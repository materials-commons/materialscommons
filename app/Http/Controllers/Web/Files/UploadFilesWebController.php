<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Files\CreateFileAction;
use App\Actions\Files\CreateFilesAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Models\ScriptTrigger;
use App\Traits\CreateDirectories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function basename;
use function dirname;

class UploadFilesWebController extends Controller
{
    use CreateDirectories;

    public function __invoke(Request $request, CreateFilesAction $createFilesAction, Project $project, $directoryId)
    {
        $validated = $request->validate([
            'files.*'      => 'nullable|file',
            'file'         => 'nullable|file',
            'relativePath' => 'nullable|string',
        ]);

//        ray($request->all());

        $dir = File::findOrFail($directoryId);

        if (isset($validated['files'])) {
            $files = $validated['files'];
            $createFilesAction($project, $dir, $files);
        } else {
            $triggers = ScriptTrigger::getProjectTriggers($project);
            $createFileAction = new CreateFileAction($triggers);
            $file = $validated['file'];
            $nameToUse = $validated['relativePath'] ?? $file->getClientOriginalName();
            $dirToUse = $dir;
            if (Str::contains($nameToUse, "/")) {
                // Find the directory this should be uploaded to. Create the directory if it
                // doesn't exist.
                $dirPath = dirname($nameToUse);
                $nameToUse = basename($nameToUse);
                $dirToUse = $this->getDirectoryOrCreateIfDoesNotExist($dir, $dirPath, $project);
            }
            $createFileAction($project, $dirToUse, '', $file, $nameToUse);
        }
    }
}
