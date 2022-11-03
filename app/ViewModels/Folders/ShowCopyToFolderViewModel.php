<?php

namespace App\ViewModels\Folders;

use App\Models\File;
use App\Models\Project;

class ShowCopyToFolderViewModel extends ShowFolderViewModel
{
    protected ?File $fromDirectory;
    protected ?string $copyType;
    protected ?Project $fromProject;
    protected $fromFiles;

    public function __construct(File $fromDirectory, File $directory, $files)
    {
        parent::__construct($directory, $files);
        $this->fromDirectory = $fromDirectory;
    }

    public function withCopyType(string $copyType): ShowCopyToFolderViewModel
    {
        $this->copyType = $copyType;
        return $this;
    }

    public function copyType(): ?string
    {
        return $this->copyType;
    }

    public function fromDirectory(): File
    {
        return $this->fromDirectory;
    }

    public function withFromProject(Project $project): ShowCopyToFolderViewModel
    {
        $this->fromProject = $project;
        return $this;
    }

    public function fromProject(): ?Project
    {
        return $this->fromProject;
    }

    public function withFromFiles($files): ShowCopyToFolderViewModel
    {
        $this->fromFiles = $files;
        return $this;
    }

    public function fromFiles()
    {
        return $this->fromFiles;
    }
}