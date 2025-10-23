<?php

namespace App\View\Components;

use App\Models\File;
use App\Models\Project;
use Illuminate\View\Component;
use function array_push;
use function explode;

class ShowDirPath extends Component
{
    public $file;
    public $dir;
    public $project;
    public $dirPaths;

    public function __construct(File $dir, Project $project, $file = null)
    {
        $this->file = $file;
        $this->dir = $dir;
        $this->project = $project;
        $this->dirPaths = [];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->createDirectoryPaths();
        return view('components.show-dir-path');
    }

    protected function createDirectoryPaths()
    {
        $directory = $this->dir;
        if (!$this->dir->isDir()) {
            if (!isset($file->directory)) {
                $this->dir->load('directory');
            }
            $directory = $this->dir->directory;
        }

        if ($directory->path === "/") {
            array_push($this->dirPaths, ['name' => '', 'path' => "/"]);
        } else {
            $pieces = explode('/', $directory->path);
            $currentPath = "";
            foreach ($pieces as $piece) {
                if ($piece === "") {
                    array_push($this->dirPaths, ['name' => '', 'path' => "/"]);
                } else {
                    $currentPath = "{$currentPath}/{$piece}";
                    array_push($this->dirPaths, ['name' => $piece, 'path' => $currentPath]);
                }
            }
        }
    }
}
