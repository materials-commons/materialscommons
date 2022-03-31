<?php

namespace App\ViewModels\Folders;

use App\Models\File;
use App\Models\Project;
use App\Traits\Notifications\NotificationChecker;
use Spatie\ViewModels\ViewModel;

class ShowFolderViewModel extends ViewModel
{
    private $directory;
    private $project;
    private $dirPaths;
    private $files;
    protected $dataset;

    public function __construct(File $directory, $files)
    {
        $this->directory = $directory;
        $this->dirPaths = [];
        $this->files = $files;
        $this->project = null;
        $this->dataset = null;

        $this->createDirectoryPaths();
    }

    public function withProject($project)
    {
        $this->project = $project;
        return $this;
    }

    public function withDataset($dataset)
    {
        $this->dataset = $dataset;
        return $this;
    }

    public function project()
    {
        return $this->project;
    }

    public function directory()
    {
        return $this->directory;
    }

    public function files()
    {
        return $this->files;
    }

    public function dataset()
    {
        return $this->dataset;
    }

    public function dirPaths()
    {
        return $this->dirPaths;
    }

    private function createDirectoryPaths()
    {
        if ($this->directory->path === "/") {
            array_push($this->dirPaths, ['name' => '', 'path' => "/"]);
        } else {
            $pieces = explode('/', $this->directory->path);
            $currentPath = "";
            foreach ($pieces as $piece) {
                if ($piece === "") {
                    array_push($this->dirPaths, ['name' => '', 'path' => "/"]);
                } else {
                    $currentPath = "{$currentPath}/${piece}";
                    array_push($this->dirPaths, ['name' => $piece, 'path' => $currentPath]);
                }
            }
        }
    }
}
