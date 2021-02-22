<?php

namespace App\Actions\Datasets;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UpdateDatasetFileSelectionAction
{
    public function __invoke($selection, $dataset)
    {
        ray("__invoke with selection", $selection);
        DB::transaction(function () use ($selection, $dataset) {
            $fileSelection = collect($dataset->file_selection);
            foreach ($selection as $selectionKey => $path) {
                switch ($selectionKey) {
                    case "include_file":
                        $fileSelection = $this->addIncludeFile($fileSelection, $path);
                        break;
                    case "remove_include_file":
                        $fileSelection = $this->removeIncludeFile($fileSelection, $path, $dataset);
                        break;
                    case "include_dir":
                        $fileSelection = $this->addIncludeDir($fileSelection, $path, $dataset);
                        break;
                    case "remove_include_dir":
                        $fileSelection = $this->removeIncludeDir($fileSelection, $path, $dataset);
                        break;
                    case "exclude_file":
                        $fileSelection = $this->addExcludeFile($fileSelection, $path, $dataset);
                        break;
                    case "remove_exclude_file":
                        $fileSelection = $this->removeExcludeFile($fileSelection, $path, $dataset);
                        break;
                    case "exclude_dir":
                        $fileSelection = $this->addExcludeDir($fileSelection, $path, $dataset);
                        break;
                    case "remove_exclude_dir":
                        $fileSelection = $this->removeExcludeDir($fileSelection, $path, $dataset);
                        break;
                }
            }

//            $fileSelection = $this->addRemoveIfExists($fileSelection, 'include_files', $selection, 'include_file');
//            $fileSelection = $this->addRemoveIfExists($fileSelection, 'exclude_files', $selection, 'exclude_file');
//            $fileSelection = $this->addRemoveIfExists($fileSelection, 'include_dirs', $selection, 'include_dir');
//            $fileSelection = $this->addRemoveIfExists($fileSelection, 'exclude_dirs', $selection, 'exclude_dir');

            $dataset->update(['file_selection' => $fileSelection]);
        });

        return $dataset->fresh();
    }

    private function addRemoveIfExists($fileSelection, $fileSelectionKey, $selection, $selectionKey)
    {
        if (array_key_exists($selectionKey, $selection)) {
            $fileSelection = $this->mergeSelectionEntry($fileSelection, $fileSelectionKey, [$selection[$selectionKey]]);
        }

        if (array_key_exists("remove_{$selectionKey}", $selection)) {
            ray("remove entry");
            $fileSelection = $this->removeSelectionEntry($fileSelection, $fileSelectionKey,
                $selection["remove_{$selectionKey}"]);
        }

        return $fileSelection;
    }

    private function mergeSelectionEntry($fileSelection, $fileSelectionKey, $selection)
    {
        $selectionEntry = collect($fileSelection->get($fileSelectionKey));
        $selectionEntry = $selectionEntry->merge($selection)->unique();
        $fileSelection->put($fileSelectionKey, $selectionEntry->toArray());
        return $fileSelection;
    }

    private function removeSelectionEntry($fileSelection, $fileSelectionKey, $entryToRemove)
    {
        $files = collect($fileSelection->get($fileSelectionKey))->reject(function ($value) use ($entryToRemove) {
            return $value == $entryToRemove;
        });
        $fileSelection->put($fileSelectionKey, $files);
        return $fileSelection;
    }

    private function addIncludeFile(Collection $fileSelection, $path): Collection
    {
        // add include file
        $includeFiles = collect($fileSelection->get('include_files'));
        $fileSelection->put('include_files', $includeFiles->merge($path)
                                                          ->unique()
                                                          ->toArray());

        // remove from exclude files if in there
        if ($fileSelection->has('exclude_files')) {
            $excludeFiles = collect($fileSelection->get('exclude_files'));
            $fileSelection->put('exclude_files', $excludeFiles->reject(fn($pathEntry) => $pathEntry == $path)
                                                              ->toArray());
        }
        return $fileSelection;
    }

    private function removeIncludeFile(Collection $fileSelection, $path, $dataset): Collection
    {
        return $fileSelection;
    }

    private function addIncludeDir(Collection $fileSelection, $path, $dataset): Collection
    {
        return $fileSelection;
    }

    private function removeIncludeDir(Collection $fileSelection, $path, $dataset): Collection
    {
        return $fileSelection;
    }

    private function addExcludeFile(Collection $fileSelection, $path, $dataset): Collection
    {
        return $fileSelection;
    }

    private function removeExcludeFile(Collection $fileSelection, $path, $dataset): Collection
    {
        return $fileSelection;
    }

    private function addExcludeDir(Collection $fileSelection, $path, $dataset): Collection
    {
        return $fileSelection;
    }

    private function removeExcludeDir(Collection $fileSelection, $path, $dataset): Collection
    {
        return $fileSelection;
    }
}