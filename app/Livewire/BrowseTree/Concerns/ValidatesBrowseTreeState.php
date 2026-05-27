<?php

namespace App\Livewire\BrowseTree\Concerns;

use function in_array;
use function is_array;

trait ValidatesBrowseTreeState
{
    private function validScope(mixed $scope): string
    {
        return in_array($scope, ['project', 'all'], true) ? $scope : $this->scope;
    }

    private function validGroupBy(mixed $groupBy): string
    {
        return in_array($groupBy, ['project', 'type'], true) ? $groupBy : 'project';
    }

    private function validDateFilter(mixed $dateFilter): string
    {
        return in_array($dateFilter, ['any', 'today', 'last-7-days', 'last-30-days', 'this-year'], true)
            ? $dateFilter
            : 'any';
    }

    private function validSelectedTypes(mixed $selectedTypes): array
    {
        if (!is_array($selectedTypes)) {
            return $this->defaultSelectedTypes();
        }

        return array_values(array_filter(
            $selectedTypes,
            fn(string $type) => in_array($type, $this->allowedTreeTypes(), true)
        ));
    }

    private function defaultSelectedTypes(): array
    {
        return [
            'sample',
            'computation',
            'file',
            'dataset',
            'experiment',
        ];
    }

    private function allowedTreeTypes(): array
    {
        return [
            'sample',
            'computation',
            'file',
            'dataset',
            'experiment',

            // Future:
            // 'user',
            // 'measurement',
            // 'attribute',
            // 'activity',
        ];
    }
}
