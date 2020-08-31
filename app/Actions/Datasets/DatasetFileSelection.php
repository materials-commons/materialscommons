<?php

namespace App\Actions\Datasets;

use App\Helpers\PathHelpers;

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
//        $this->selection->put('file_dirs', collect());

        $this->loadSelectionEntry($datasetSelection, "include_files");
        $this->loadSelectionEntry($datasetSelection, "exclude_files");
        $this->loadSelectionEntry($datasetSelection, "include_dirs");
        $this->loadSelectionEntry($datasetSelection, "exclude_dirs");
//        $this->loadFileDirs();
    }

    private function loadSelectionEntry($datasetSelection, $selectionKey)
    {
        $includeEntries = $this->selection->get($selectionKey);

        if (!isset($datasetSelection[$selectionKey])) {
            $datasetSelection[$selectionKey] = [];
        }

        collect($datasetSelection[$selectionKey])->each(function ($path) use ($includeEntries) {
            $includeEntries->put($path, true);
        });
    }

    private function loadFileDirs()
    {
        $this->selection->get('include_files')->each(function ($ignore, $fpath) {
            $dirName = dirname($fpath);
            for (; ;) {
                if (blank($dirName)) {
                    break;
                }

                if ($dirName === "/") {
                    break;
                }

                if (!$this->selection['file_dirs']->has($dirName)) {
                    $this->selection['file_dirs']->put($dirName, true);
                }

                $dirName = dirname($dirName);
            }
        });
    }

    public function isIncludedFile($filePath)
    {
        if (blank($filePath)) {
            return false;
        }

        $path = PathHelpers::normalizePath($filePath);
        $includeFiles = $this->selection->get('include_files');
        $excludeFiles = $this->selection->get('exclude_files');

        if ($includeFiles->has($path)) {
            return true;
        }

        if ($excludeFiles->has($path)) {
            return false;
        }

        return $this->isIncludedDir(dirname($path));
    }

    public function isIncludedDir($dirPath)
    {
        $path = PathHelpers::normalizePath($dirPath);
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
            if (blank($dirName)) {
                return false;
            }

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

//    public function showDir($dirpath)
//    {
//        if ($this->isIncludedDir($dirpath)) {
//            return true;
//        }
//
//        return $this->selection['file_dirs']->has($dirpath);
//    }
}