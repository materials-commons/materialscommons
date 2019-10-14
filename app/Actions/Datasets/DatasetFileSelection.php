<?php

namespace App\Actions\Datasets;

class DatasetFileSelection
{
    private $selection;

    public function __construct($datasetSelection)
    {
        $this->selection = collect();
        $this->selection->put('include_files', collect());
        $this->selection->put('exclude_files', collect());
        $this->selection->put('include_dirs', collect());
        $this->selection->put('exclude_dirs', collect());

        $this->loadSelectionEntry($datasetSelection, "include_files");
        $this->loadSelectionEntry($datasetSelection, "exclude_files");
        $this->loadSelectionEntry($datasetSelection, "include_dirs");
        $this->loadSelectionEntry($datasetSelection, "exclude_dirs");
    }

    private function loadSelectionEntry($datasetSelection, $selectionKey)
    {
        $includeEntries = $this->selection->get($selectionKey);
        collect($datasetSelection[$selectionKey])->each(function($path) use ($includeEntries) {
            $includeEntries->put($path, true);
        });
    }

    public function isIncludedFile($filePath)
    {
        $includeFiles = $this->selection->get('include_files');
        $excludeFiles = $this->selection->get('exclude_files');

        if ($includeFiles->has($filePath)) {
            return true;
        }

        if ($excludeFiles->has($filePath)) {
            return false;
        }

        return $this->isIncludedDir(dirname($filePath));
    }

    public function isIncludedDir($path)
    {
        $includeDirs = $this->selection->get('include_dirs');
        $excludeDirs = $this->selection->get('exclude_dirs');

        if ($includeDirs->has($path)) {
            return true;
        }

        if ($excludeDirs->has($path)) {
            return false;
        }

        $dirName = dirname($path);
        for (; ;) {
            if ($includeDirs->has($dirName)) {
                return true;
            }

            if ($excludeDirs->has($dirName)) {
                return false;
            }

            if ($dirName === "/") {
                return false;
            }

            $dirName = dirname($dirName);
        }
    }

    /*
     *    // isIncludedDir checks if the directory is in the include or exclude directories lists. If its
    // not then it returns parent.selected as that will denote the child state.
    isIncludedDir(dirPath, parent) {
        if ((dirPath in this.state.selection.include_dirs)) {
            return true;
        } else if ((dirPath in this.state.selection.exclude_dirs)) {
            return false;
        }

        return parent.selected;
    }

    // isIncludedFile checks if the file is in the include or exclude lists. If not then
    // it checks whether the directory the file is has been selected.
    isIncludedFile(filePath, dir) {
        if ((filePath in this.state.selection.include_files)) {
            return true;
        } else if ((filePath in this.state.selection.exclude_files)) {
            return false;
        } else {
            return dir.selected;
        }
    }
     */
}