<?php

namespace App\Actions\Datasets;

use Illuminate\Support\Facades\DB;

class UpdateDatasetSelectionAction
{
    public function __invoke($selection, $dataset)
    {
        DB::transaction(function() use ($selection, $dataset) {
            $fileSelection = collect($dataset->file_selection);
            $fileSelection = $this->addRemoveIfExists($fileSelection, 'include_files', $selection, 'include_file');
            $fileSelection = $this->addRemoveIfExists($fileSelection, 'exclude_files', $selection, 'exclude_file');
            $fileSelection = $this->addRemoveIfExists($fileSelection, 'include_dirs', $selection, 'include_dir');
            $fileSelection = $this->addRemoveIfExists($fileSelection, 'exclude_dirs', $selection, 'exclude_dir');

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
        $files = collect($fileSelection->get($fileSelectionKey))->reject(function($value) use ($entryToRemove) {
            return $value == $entryToRemove;
        });
        $fileSelection->put($fileSelectionKey, $files);
        return $fileSelection;
    }
}