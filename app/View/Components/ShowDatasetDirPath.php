<?php

namespace App\View\Components;

//use Illuminate\View\Component;

use App\Models\Dataset;
use App\Models\File;

class ShowDatasetDirPath extends ShowDirPath
{
    public $dataset;

    public function __construct(File $file, Dataset $dataset)
    {
        $dataset->load('project');
        parent::__construct($file, $dataset->project);
        $this->dataset = $dataset;
    }

    public function render()
    {
        $this->createDirectoryPaths();
        return view('components.show-dataset-dir-path');
    }
}
