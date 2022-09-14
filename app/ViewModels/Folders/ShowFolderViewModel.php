<?php

namespace App\ViewModels\Folders;

use App\Models\File;
use Spatie\ViewModels\ViewModel;

class ShowFolderViewModel extends ViewModel
{
    private $directory;
    private $project;
    private $files;
    protected $dataset;

    public function __construct(File $directory, $files)
    {
        $this->directory = $directory;
        $this->files = $files;
        $this->project = null;
        $this->dataset = null;
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
}
