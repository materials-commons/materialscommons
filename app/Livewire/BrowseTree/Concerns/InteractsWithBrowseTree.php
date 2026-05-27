<?php

namespace App\Livewire\BrowseTree\Concerns;

use App\Actions\BrowseTree\BuildBrowseTreeAction;
use App\Actions\BrowseTree\Support\BrowseTreeFilter;
use App\Actions\BrowseTree\Support\BrowseTreeGrouper;
use App\Models\Project;
use function in_array;
use function str_starts_with;

trait InteractsWithBrowseTree
{
    public function updatedScope(string $scope): void
    {
        $this->setScope($scope);
    }

    public function setScope(string $scope): void
    {
        if (!in_array($scope, ['project', 'all'], true)) {
            return;
        }

        if ($scope === 'project' && $this->project === null && $this->focusedProjectId === null) {
            $this->scope = 'all';
            $this->persistBrowserState();
            return;
        }

        $this->scope = $scope;
        $this->selectedItem = null;
        $this->selectedItemKey = null;

        if ($scope === 'all') {
            $this->expandedNodeKeys = [];
            $this->persistBrowserState();
            return;
        }

        $this->expandedNodeKeys = ['current-project'];
        $this->persistBrowserState();
    }

    public function setQuickFilter(string $quickFilter): void
    {
        $this->quickFilter = $quickFilter;

        $this->search = match ($quickFilter) {
            'recent' => 'recent',
            'pinned' => 'pinned',
            default => '',
        };

        if ($this->search !== '') {
            $this->expandMatchingParents();
        }

        $this->persistBrowserState();
    }

    public function setDateFilter(string $dateFilter): void
    {
        if (!in_array($dateFilter, ['any', 'today', 'last-7-days', 'last-30-days', 'this-year'], true)) {
            return;
        }

        $this->dateFilter = $dateFilter;
        $this->expandMatchingParents();
        $this->persistBrowserState();
    }

    public function setExperimentFilter(string $experimentFilter): void
    {
        $this->experimentFilter = $experimentFilter;
        $this->expandMatchingParents();
        $this->persistBrowserState();
    }

    public function toggleTag(string $tag): void
    {
        if (in_array($tag, $this->selectedTags, true)) {
            $this->selectedTags = array_values(array_filter(
                $this->selectedTags,
                fn(string $selectedTag) => $selectedTag !== $tag
            ));

            $this->persistBrowserState();
            return;
        }

        $this->selectedTags[] = $tag;
        $this->expandMatchingParents();
        $this->persistBrowserState();
    }

    public function clearFacets(): void
    {
        $this->dateFilter = 'any';
        $this->experimentFilter = 'any';
        $this->selectedTags = [];
        $this->persistBrowserState();
    }

    public function toggleNode(string $key): void
    {
        if (str_starts_with($key, 'project-')) {
            $this->rememberFocusedProjectFromNodeKey($key);
        }

        if (in_array($key, $this->expandedNodeKeys, true)) {
            $this->expandedNodeKeys = array_values(array_filter(
                $this->expandedNodeKeys,
                fn(string $expandedKey) => $expandedKey !== $key
            ));

            $this->persistBrowserState();
            return;
        }

        $this->expandedNodeKeys[] = $key;
        $this->persistBrowserState();
    }

    public function expandAll(): void
    {
        $buildBrowseTreeAction = app(BuildBrowseTreeAction::class);
        $tree = $this->treeData($buildBrowseTreeAction);

        $this->expandedNodeKeys = array_values(array_unique([
            ...$this->expandedNodeKeys,
            ...collect($tree)->pluck('key')->all(),
        ]));

        $this->persistBrowserState();
    }

    public function collapseAll(): void
    {
        $this->expandedNodeKeys = [];
        $this->persistBrowserState();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->quickFilter = 'all';
        $this->persistBrowserState();
    }

    public function selectItem(string $key): void
    {
        $buildBrowseTreeAction = app(BuildBrowseTreeAction::class);

        $this->selectedItemKey = $key;
        $this->selectedItem = $this->findNodeByKey(
            $this->treeData($buildBrowseTreeAction),
            $key
        );

        $this->persistBrowserState();
    }

    public function selectNode(string $key): void
    {
        $this->selectItem($key);
    }

    public function setSuggestedSearch(string $search): void
    {
        $this->search = $search;
        $this->quickFilter = 'all';
        $this->expandMatchingParents();
        $this->persistBrowserState();
    }

    public function toggleDirectoryFiles(string $directoryKey): void
    {
        if (in_array($directoryKey, $this->directoriesWithVisibleFiles, true)) {
            $this->directoriesWithVisibleFiles = array_values(array_filter(
                $this->directoriesWithVisibleFiles,
                fn(string $visibleDirectoryKey) => $visibleDirectoryKey !== $directoryKey
            ));

            $this->persistBrowserState();
            return;
        }

        $this->directoriesWithVisibleFiles[] = $directoryKey;
        $this->persistBrowserState();
    }

    public function updatedAlwaysShowFiles(): void
    {
        if (!$this->alwaysShowFiles) {
            $this->directoriesWithVisibleFiles = [];
        }

        $this->persistBrowserState();
    }

    public function updatedSearch(): void
    {
        $this->quickFilter = 'all';

        if ($this->search !== '') {
            $this->expandMatchingParents();
        }

        $this->persistBrowserState();
    }

    public function updatedSelectedTypes(): void
    {
        $this->expandMatchingParents();
        $this->persistBrowserState();
    }

    public function updatedGroupBy(): void
    {
        if (!in_array($this->groupBy, ['project', 'type'], true)) {
            $this->groupBy = 'project';
        }

        $this->selectedItem = null;
        $this->selectedItemKey = null;

        $buildBrowseTreeAction = app(BuildBrowseTreeAction::class);
        $grouper = app(BrowseTreeGrouper::class);
        $tree = $this->treeData($buildBrowseTreeAction);
        $groupedTree = $this->groupBy === 'type' ? $grouper->byType($tree) : $tree;

        $this->expandedNodeKeys = array_values(array_unique([
            ...$this->expandedNodeKeys,
            ...array_slice($this->collectFolderKeys($groupedTree), 0, 8),
        ]));

        $this->persistBrowserState();
    }

    private function rememberFocusedProjectFromNodeKey(string $key): void
    {
        $projectId = (int) str_replace('project-', '', $key);

        if ($projectId <= 0) {
            return;
        }

        $project = Project::query()
                          ->select(['id', 'name'])
                          ->find($projectId);

        if ($project === null) {
            return;
        }

        $this->focusedProjectId = $project->id;
        $this->focusedProjectName = $project->name;
    }

    private function expandMatchingParents(): void
    {
        $buildBrowseTreeAction = app(BuildBrowseTreeAction::class);
        $filter = app(BrowseTreeFilter::class);
        $grouper = app(BrowseTreeGrouper::class);

        $tree = $this->treeData($buildBrowseTreeAction);
        $groupedTree = $this->groupBy === 'type' ? $grouper->byType($tree) : $tree;
        $expanded = [];

        $walk = function (array $nodes, array $parents = []) use (&$walk, &$expanded, $filter): bool {
            $hasMatch = false;

            foreach ($nodes as $node) {
                $childHasMatch = $walk($node['children'] ?? [], [...$parents, $node['key']]);
                $nodeMatches = $filter->nodeMatches($node, $this->filterState());

                if ($nodeMatches || $childHasMatch) {
                    $hasMatch = true;
                    $expanded = [...$expanded, ...$parents, $node['key']];
                }
            }

            return $hasMatch;
        };

        $walk($groupedTree);

        $this->expandedNodeKeys = array_values(array_unique($expanded));
    }
}
