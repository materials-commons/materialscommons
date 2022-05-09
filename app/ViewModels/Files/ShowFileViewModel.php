<?php

namespace App\ViewModels\Files;

use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use Spatie\ViewModels\ViewModel;

class ShowFileViewModel extends ViewModel
{
    use FileView;

    private ?File $file;

    private ?Project $project;

    /**
     * @var \App\Models\Dataset | null
     */
    private ?Dataset $dataset;

    private $previousVersions;

    public function __construct(File $file, $project, $dataset = null)
    {
        $this->file = $file;
        $this->project = $project;
        $this->dataset = $dataset;
        $this->previousVersions = collect();
    }

    public function project()
    {
        return $this->project;
    }

    public function file(): File
    {
        return $this->file;
    }

    public function dataset()
    {
        return $this->dataset;
    }

    public function withPreviousVersions($previousVersions)
    {
        $this->previousVersions = $previousVersions;
        return $this;
    }

    public function previousVersions()
    {
        return $this->previousVersions;
    }

    public function fileExtension()
    {
        $pathinfo = pathinfo($this->file->name);
        if (isset($pathinfo["extension"])) {
            return $pathinfo["extension"];
        }

        return "";
    }
}
