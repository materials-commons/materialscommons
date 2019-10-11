<?php

namespace App\Actions\Datasets;

use Illuminate\Support\Facades\DB;

class UpdateDatasetSelectionAction
{
    public function __invoke($selection, $dataset)
    {
        DB::transaction(function() use ($selection, $dataset) {
            $fileSelection = collect($dataset->file_selection);
            if (array_key_exists('include_file', $selection)) {
                $fileSelection = $this->mergeSelectionEntry($fileSelection, 'include_files',
                    [$selection["include_file"]]);
            }

            if (array_key_exists('remove_include_file', $selection)) {
                $fileSelection = $this->removeSelectionEntry($fileSelection, 'include_files',
                    $selection["remove_include_file"]);
            }

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
        $selectionEntry = $selectionEntry->merge($selection);
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