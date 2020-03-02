<?php

namespace App\Traits\Controllers\Datasets;

use App\Actions\Datasets\GetDatasetFilesAction;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\ViewModels\Datasets\EditDatasetViewModel;

class EditDatasetViewModelBuilder
{
    /**
     * @var \App\ViewModels\Datasets\EditDatasetViewModel
     */
    private $viewModel;

    /**
     * @var \App\Models\User
     */
    private $user = null;

    public function withUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function buildWithFolder($projectId, $datasetId, $path, $folderId)
    {
        $dataset = $this->getDataset($datasetId);
        $project = $this->getProject($projectId);
        $folder = $this->getFolder($path, $folderId, $projectId);
        $getDatasetFilesAction = new GetDatasetFilesAction($dataset->file_selection);
        $filesAndDir = $getDatasetFilesAction($project->id, $folder);
        $directory = $filesAndDir["directory"];
        $files = $filesAndDir["files"];
        $viewModel = new EditDatasetViewModel($project, $dataset, auth()->user());
        $viewModel->withCommunities($this->getProject())
                  ->withExperiments($project->experiments)
                  ->withFiles($files)
                  ->withDirectory($directory);
        return $viewModel;
    }

    public function buildWithSamples($projectId, $datasetId)
    {
    }

    public function buildWithActivities($projectId, $datasetId)
    {
    }

    public function buildWithWorkflows($projectId, $datasetId)
    {
    }

    private function getProject($projectId, $with = [])
    {
        $projectWith = array_merge(['experiments'], $with);
        return Project::with($projectWith)->findOrFail($projectId);
    }

    private function getDataset($datasetId, $with = [])
    {
        $dsWith = array_merge(['communities', 'tags', 'experiments'], $with);
        return Dataset::with($dsWith)->findOrFail($datasetId);
    }

    private function getPublicCommunities()
    {
        return Community::where('public', true)->get();
    }

    private function getFolder($path, $folderId, $projectId)
    {
        if ($path === null && $folderId === null) {
            return "/";
        }

        if ($folderId !== null) {
            return $folderId;
        }

        $folder = File::where('project_id', $projectId)->where('path', $path)
                      ->where('mime_type', 'directory')
                      ->first();
        return $folder->id;
    }
}