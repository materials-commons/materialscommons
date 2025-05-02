<?php

namespace App\Actions\Datasets;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UpdateDatasetFileSelectionAction
{
    public function __invoke($selection, $dataset)
    {
        DB::transaction(function () use ($selection, $dataset) {
            $fileSelection = collect($dataset->file_selection);
            foreach ($selection as $selectionKey => $path) {
                switch ($selectionKey) {
                    case "include_file":
                        $fileSelection = $this->addIncludeFile($fileSelection, $path);
                        break;
                    case "remove_include_file":
                        $fileSelection = $this->removeIncludeFile($fileSelection, $path);
                        break;
                    case "include_dir":
                        $fileSelection = $this->addIncludeDir($fileSelection, $path);
                        break;
                    case "remove_include_dir":
                        $fileSelection = $this->removeIncludeDir($fileSelection, $path);
                        break;
                    case "exclude_file":
                        $fileSelection = $this->addExcludeFile($fileSelection, $path);
                        break;
                    case "remove_exclude_file":
                        $fileSelection = $this->removeExcludeFile($fileSelection, $path);
                        break;
                    case "exclude_dir":
                        $fileSelection = $this->addExcludeDir($fileSelection, $path);
                        break;
                    case "remove_exclude_dir":
                        $fileSelection = $this->removeExcludeDir($fileSelection, $path);
                        break;
                    case "include_entity_file":
                        $fileSelection = $this->addEntityIncludeFile($fileSelection, $path);
                        break;
                    case "remove_entity_file":
                        $fileSelection = $this->removeEntityIncludeFile($fileSelection, $path);
                        break;
                    case "include_entity_dir":
                        $fileSelection = $this->addEntityIncludeDir($fileSelection, $path);
                        break;
                    case "remove_entity_dir":
                        $fileSelection = $this->removeEntityIncludeDir($fileSelection, $path);
                        break;
                }
            }

            $dataset->update(['file_selection' => $fileSelection]);
        });

        return $dataset->fresh();
    }

    private function addIncludeFile(Collection $fileSelection, $path): Collection
    {
        // add include file
        $fileSelection = $this->addItemToSelectionKey($fileSelection, 'include_files', $path);

        // remove from exclude files if in there
        $fileSelection = $this->removeItemFromSelectionKey($fileSelection, 'exclude_files', $path);

        return $fileSelection;
    }

    private function removeIncludeFile(Collection $fileSelection, $path): Collection
    {
        // remove from include_files
        $fileSelection = $this->removeItemFromSelectionKey($fileSelection, 'include_files', $path);

        // Add to exclude files
        $fileSelection = $this->addItemToSelectionKey($fileSelection, 'exclude_files', $path);

        return $fileSelection;
    }

    private function addIncludeDir(Collection $fileSelection, $path): Collection
    {
        $fileSelection = $this->addItemToSelectionKey($fileSelection, 'include_dirs', $path);

        // Remove from exclude list
        $fileSelection = $this->removeItemFromSelectionKey($fileSelection, 'exclude_dirs', $path);
        return $fileSelection;
    }

    private function removeIncludeDir(Collection $fileSelection, $path): Collection
    {
        $fileSelection = $this->removeItemFromSelectionKey($fileSelection, 'include_dirs', $path);
        $fileSelection = $this->addItemToSelectionKey($fileSelection, 'exclude_dirs', $path);
        return $fileSelection;
    }

    private function addEntityIncludeFile(Collection $fileSelection, $path): Collection
    {
        // add include file
        $fileSelection = $this->addItemToSelectionKey($fileSelection, 'entity_include_files', $path);

        // remove from exclude files if in there
        $fileSelection = $this->removeItemFromSelectionKey($fileSelection, 'entity_exclude_files', $path);

        return $fileSelection;
    }

    private function removeEntityIncludeFile(Collection $fileSelection, $path): Collection
    {
        // remove from include_files
        $fileSelection = $this->removeItemFromSelectionKey($fileSelection, 'entity_include_files', $path);

        // Add to exclude files
        $fileSelection = $this->addItemToSelectionKey($fileSelection, 'entity_exclude_files', $path);

        return $fileSelection;
    }

    private function addEntityIncludeDir(Collection $fileSelection, $path): Collection
    {
        $fileSelection = $this->addItemToSelectionKey($fileSelection, 'entity_include_dirs', $path);

        // Remove from exclude list
        $fileSelection = $this->removeItemFromSelectionKey($fileSelection, 'entity_exclude_dirs', $path);
        return $fileSelection;
    }

    private function removeEntityIncludeDir(Collection $fileSelection, $path): Collection
    {
        $fileSelection = $this->removeItemFromSelectionKey($fileSelection, 'entity_include_dirs', $path);
        $fileSelection = $this->addItemToSelectionKey($fileSelection, 'entity_exclude_dirs', $path);
        return $fileSelection;
    }

    private function addExcludeFile(Collection $fileSelection, $path): Collection
    {
        $fileSelection = $this->addItemToSelectionKey($fileSelection, 'exclude_files', $path);
        $fileSelection = $this->removeItemFromSelectionKey($fileSelection, 'include_files', $path);
        return $fileSelection;
    }

    private function removeExcludeFile(Collection $fileSelection, $path): Collection
    {
        $fileSelection = $this->removeItemFromSelectionKey($fileSelection, 'exclude_files', $path);
        return $fileSelection;
    }

    private function addExcludeDir(Collection $fileSelection, $path): Collection
    {
        // Not needed at the moment for the api
        return $fileSelection;
    }

    private function removeExcludeDir(Collection $fileSelection, $path): Collection
    {
        // Not needed at the moment for the api
        return $fileSelection;
    }

    private function addItemToSelectionKey(Collection $fileSelection, $fileSelectionKey, $path): Collection
    {
        $fileSelectionItem = collect($fileSelection->get($fileSelectionKey));
        $fileSelection->put($fileSelectionKey, $fileSelectionItem->merge($path)
                                                                 ->unique()
                                                                 ->toArray());
        return $fileSelection;
    }

    private function removeItemFromSelectionKey(Collection $fileSelection, $fileSelectionKey, $path): Collection
    {
        $fileSelectionItem = collect($fileSelection->get($fileSelectionKey));
        $fileSelection->put($fileSelectionKey, $fileSelectionItem->reject(fn($pathEntry) => $pathEntry == $path)
                                                                 ->toArray());
        return $fileSelection;
    }
}