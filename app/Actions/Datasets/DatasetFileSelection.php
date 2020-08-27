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

        $this->loadSelectionEntry($datasetSelection, "include_files");
        $this->loadSelectionEntry($datasetSelection, "exclude_files");
        $this->loadSelectionEntry($datasetSelection, "include_dirs");
        $this->loadSelectionEntry($datasetSelection, "exclude_dirs");
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
}