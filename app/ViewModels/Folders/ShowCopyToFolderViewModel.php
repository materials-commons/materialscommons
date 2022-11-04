<?php

namespace App\ViewModels\Folders;

use App\Models\File;
use App\Models\Project;
use Spatie\ViewModels\ViewModel;

class ShowCopyToFolderViewModel extends ViewModel
{
    protected ?File $leftDirectory;
    protected ?Project $leftProject;
    protected $leftFiles;
    protected ?File $rightDirectory;
    protected ?Project $rightProject;
    protected $rightFiles;

    public function withLeftDirectory(File $dir): ShowCopyToFolderViewModel
    {
        $this->leftDirectory = $dir;
        return $this;
    }

    public function leftDirectory(): File
    {
        return $this->leftDirectory;
    }

    public function withLeftProject(Project $project): ShowCopyToFolderViewModel
    {
        $this->leftProject = $project;
        return $this;
    }

    public function leftProject(): ?Project
    {
        return $this->leftProject;
    }

    // To show the nav we must have a $project. $leftProject is the current project, so
    // we create an accessor that allows access to $leftProject as $project.
    public function project(): ?Project
    {
        return $this->leftProject;
    }

    public function withLeftFiles($files): ShowCopyToFolderViewModel
    {
        $this->leftFiles = $files;
        return $this;
    }

    public function leftFiles()
    {
        return $this->leftFiles;
    }

    public function withRightDirectory(File $dir): ShowCopyToFolderViewModel
    {
        $this->rightDirectory = $dir;
        return $this;
    }

    public function rightDirectory(): File
    {
        return $this->rightDirectory;
    }

    public function withRightProject(Project $project): ShowCopyToFolderViewModel
    {
        $this->rightProject = $project;
        return $this;
    }

    public function rightProject(): ?Project
    {
        return $this->rightProject;
    }

    public function withRightFiles($files): ShowCopyToFolderViewModel
    {
        $this->rightFiles = $files;
        return $this;
    }

    public function rightFiles()
    {
        return $this->rightFiles;
    }
}