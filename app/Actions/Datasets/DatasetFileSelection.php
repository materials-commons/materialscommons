<?php

namespace App\Actions\Datasets;

use App\Helpers\PathHelpers;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\File;

class DatasetFileSelection
{
    private $selection;
    private $dataset;

    public function __construct($datasetSelection, ?Dataset $dataset = null)
    {
        $this->dataset = $dataset;
        $this->selection = collect();
        $this->selection->put('include_files', collect());
        $this->selection->put('exclude_files', collect());
        $this->selection->put('include_dirs', collect());
        $this->selection->put('exclude_dirs', collect());
        $this->selection->put('entity_files', collect());

        $this->loadSelectionEntry($datasetSelection, "include_files");
        $this->loadSelectionEntry($datasetSelection, "exclude_files");
        $this->loadSelectionEntry($datasetSelection, "include_dirs");
        $this->loadSelectionEntry($datasetSelection, "exclude_dirs");
        $this->loadDatasetEntitiesFiles();
    }

    private function loadDatasetEntitiesFiles()
    {
        if (is_null($this->dataset)) {
            return;
        }

        $entityFiles = $this->selection->get("entity_files");
        $entities = $this->dataset->entitiesFromTemplate();
        $entities->each(function (Entity $entity) use ($entityFiles) {
            $entity->files->each(function (File $file) use ($entityFiles) {
                $path = $file->fullPath();
                if (!$entityFiles->has($path)) {
                    $entityFiles->put($path, true);
                }
            });
        });
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
        $entityFiles = $this->selection->get("entity_files");

        if ($includeFiles->has($path)) {
            return true;
        }

        if ($excludeFiles->has($path)) {
            return false;
        }

        // Check after exclude files check so that exclude files overrides what is in the entity files.
        // It is done this way because there is no way to exclude files from the entities unless there are
        // placed in the exclude_files list. This allows entities to keep their list of files but the user
        // can choose some files in an entity to be excluded file the dataset.
        if ($entityFiles->has($path)) {
            return true;
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
