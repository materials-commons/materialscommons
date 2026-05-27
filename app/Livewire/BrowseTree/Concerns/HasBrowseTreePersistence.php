<?php

namespace App\Livewire\BrowseTree\Concerns;

use App\Actions\BrowseTree\BuildBrowseTreeAction;
use function is_array;
use function is_numeric;

trait HasBrowseTreePersistence
{
    public function exportBrowserState(): array
    {
        return [
            'scope' => $this->scope,
            'search' => $this->search,
            'selectedTypes' => $this->selectedTypes,
            'groupBy' => $this->groupBy,
            'dateFilter' => $this->dateFilter,
            'experimentFilter' => $this->experimentFilter,
            'selectedTags' => $this->selectedTags,
            'alwaysShowFiles' => $this->alwaysShowFiles,
            'quickFilter' => $this->quickFilter,
            'expandedNodeKeys' => $this->expandedNodeKeys,
            'directoriesWithVisibleFiles' => $this->directoriesWithVisibleFiles,
            'selectedItemKey' => $this->selectedItemKey,
            'focusedProjectId' => $this->focusedProjectId,
            'focusedProjectName' => $this->focusedProjectName,
        ];
    }

    public function restoreBrowserState(array $state): void
    {
        $this->scope = $this->validScope($state['scope'] ?? $this->scope);
        $this->search = (string) ($state['search'] ?? $this->search);
        $this->selectedTypes = $this->validSelectedTypes($state['selectedTypes'] ?? $this->selectedTypes);
        $this->groupBy = $this->validGroupBy($state['groupBy'] ?? $this->groupBy);
        $this->dateFilter = $this->validDateFilter($state['dateFilter'] ?? $this->dateFilter);
        $this->experimentFilter = (string) ($state['experimentFilter'] ?? $this->experimentFilter);
        $this->selectedTags = is_array($state['selectedTags'] ?? null) ? array_values($state['selectedTags']) : [];
        $this->alwaysShowFiles = (bool) ($state['alwaysShowFiles'] ?? $this->alwaysShowFiles);
        $this->quickFilter = (string) ($state['quickFilter'] ?? $this->quickFilter);
        $this->expandedNodeKeys = is_array($state['expandedNodeKeys'] ?? null) ? array_values($state['expandedNodeKeys']) : [];
        $this->directoriesWithVisibleFiles = is_array($state['directoriesWithVisibleFiles'] ?? null)
            ? array_values($state['directoriesWithVisibleFiles'])
            : [];

        if ($this->project === null) {
            $focusedProjectId = $state['focusedProjectId'] ?? null;
            $this->focusedProjectId = is_numeric($focusedProjectId) ? (int) $focusedProjectId : null;
            $this->focusedProjectName = isset($state['focusedProjectName'])
                ? (string) $state['focusedProjectName']
                : null;
        }

        $this->selectedItemKey = isset($state['selectedItemKey']) ? (string) $state['selectedItemKey'] : null;
        $this->selectedItem = null;

        if ($this->selectedItemKey !== null && $this->selectedItemKey !== '') {
            $buildBrowseTreeAction = app(BuildBrowseTreeAction::class);
            $this->selectedItem = $this->findNodeByKey(
                $this->treeData($buildBrowseTreeAction),
                $this->selectedItemKey
            );
        }

        $this->persistBrowserState();
    }

    public function resetBrowserState(): void
    {
        $this->search = '';
        $this->selectedTypes = $this->defaultSelectedTypes();
        $this->groupBy = 'project';
        $this->dateFilter = 'any';
        $this->experimentFilter = 'any';
        $this->selectedTags = [];
        $this->quickFilter = 'all';
        $this->alwaysShowFiles = false;
        $this->directoriesWithVisibleFiles = [];
        $this->selectedItemKey = null;
        $this->selectedItem = null;

        if ($this->project !== null) {
            $this->scope = 'project';
            $this->focusedProjectId = $this->project->id;
            $this->focusedProjectName = $this->project->name;
            $this->expandedNodeKeys = ['current-project'];
        } else {
            $this->scope = 'all';
            $this->focusedProjectId = null;
            $this->focusedProjectName = null;
            $this->expandedNodeKeys = [];
        }

        $this->dispatch('browse-tree-state-reset', key: $this->persistenceKey);
    }

    private function persistBrowserState(): void
    {
        $this->dispatch(
            'browse-tree-state-changed',
            key: $this->persistenceKey,
            state: $this->exportBrowserState(),
        );
    }
}
