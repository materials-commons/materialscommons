<?php

namespace App\Http\Controllers\Web\Datasets\Files;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;

class ShowDatasetFilesDirectoryWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset, $folderId)
    {
        error_log('ShowDatasetFilesDirectoryId');
        $directory = File::where('project_id', $project->id)->where('id', $folderId)->first();
        $files = $this->geFolderFiles($project->id, $directory->id);

        return view('app.projects.datasets.folders.index', compact('project', 'dataset', 'files', 'directory'));
    }

    private function geFolderFiles($projectId, $folderId)
    {
        return File::where('project_id', $projectId)
                   ->where('directory_id', $folderId)
                   ->where('current', true)
                   ->get();
    }
}
