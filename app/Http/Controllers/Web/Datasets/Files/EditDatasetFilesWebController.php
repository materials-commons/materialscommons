<?php

namespace App\Http\Controllers\Web\Datasets\Files;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;

class EditDatasetFilesWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset)
    {
        $directory = File::where('project_id', $project->id)->where('name', '/')->first();
        $files = $this->getRootFolderFiles($project->id, $directory->id);
        $user = auth()->user();

        return view('app.projects.datasets.folders.index', compact('project', 'dataset', 'files', 'directory', 'user'));
    }

    private function getRootFolderFiles($projectId, $rootId)
    {
        return File::where('project_id', $projectId)
                   ->where('directory_id', $rootId)
                   ->where('current', true)
                   ->get();
    }
}
