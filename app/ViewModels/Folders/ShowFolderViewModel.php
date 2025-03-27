<?php

namespace App\ViewModels\Folders;

use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Traits\FileView;
use Spatie\ViewModels\ViewModel;

class ShowFolderViewModel extends ViewModel
{
    use FileView;

    protected ?File $directory;
    protected ?Project $project;
    protected ?Project $destinationProject;
    protected $files;
    protected ?Dataset $dataset;
    protected $projects;
    protected $readme;
    protected $scripts;

    public function __construct(?File $directory, $files)
    {
        $this->directory = $directory;
        $this->files = $files;
        $this->project = null;
        $this->dataset = null;
        $this->readme = null;
        $this->scripts = collect();
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

    public function withReadme($file)
    {
        $this->readme = $file;
        return $this;
    }

    public function withProjects($projects): ShowFolderViewModel
    {
        $this->projects = $projects;
        return $this;
    }

    public function withScripts($scripts): ShowFolderViewModel
    {
        $this->scripts = $scripts;
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

    public function projects()
    {
        return $this->projects;
    }

    public function readme()
    {
        return $this->readme;
    }

    public function scripts()
    {
        return $this->scripts;
    }
}
