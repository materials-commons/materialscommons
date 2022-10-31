<?php

namespace App\ViewModels\Folders;

use App\Models\File;

class ShowCopyToFolderViewModel extends ShowFolderViewModel
{
    protected ?File $fromDirectory;

    public function __construct(File $fromDirectory, File $directory, $files)
    {
        parent::__construct($directory, $files);
        $this->fromDirectory = $fromDirectory;
    }

    public function fromDirectory(): File
    {
        return $this->fromDirectory;
    }
}