<?php

namespace App\ViewModels\Folders;

use App\Models\File;

class ShowCopyToFolderViewModel extends ShowFolderViewModel
{
    protected ?File $fromDirectory;
    protected ?string $copyType;

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
}