<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\UpdateDatasetFileSelectionAction;
use App\Actions\Files\CreateFilesAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Models\ScriptTrigger;
use Illuminate\Http\Request;

// Upload file(s) and set each as selected in the dataset
class UploadFilesForDatasetWebController extends Controller
{
    public function __invoke(Request $request, CreateFilesAction $createFilesAction,
                             UpdateDatasetFileSelectionAction $updateFileSelectionAction, Project $project,
                             Dataset                          $dataset, $directoryId)
    {
        $validated = $request->validate([
            'files.*' => 'required|file',
        ]);

        $directory = File::findOrFail($directoryId);
        $files = $createFilesAction($project, $directory, $validated['files']);

        // If in root dir then path is blank because construction of the file path includes a '/' separator.
        $path = $directory->path === '/' ? '' : $directory->path;

        // Set each file that was uploaded in the dataset context as selected
        foreach ($files as $file) {
            $updateFileSelectionAction([
                'project_id' => $project->id,
                'include_file' => "{$path}/{$file->name}",
            ], $dataset);
        }
    }
}
