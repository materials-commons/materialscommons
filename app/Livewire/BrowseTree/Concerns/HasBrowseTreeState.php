<?php

namespace App\Livewire\BrowseTree\Concerns;

use App\Actions\BrowseTree\BuildBrowseTreeAction;
use function auth;
use function json_encode;
use function md5;

trait HasBrowseTreeState
{
    private ?array $cachedTreeData = null;

    private ?string $cachedTreeDataKey = null;

    public function getPersistenceKeyProperty(): string
    {
        if ($this->project !== null) {
            return "mc_browse_tree_project_{$this->project->id}";
        }

        return 'mc_browse_tree_dashboard';
    }

    private function treeData(BuildBrowseTreeAction $buildBrowseTreeAction): array
    {
        $cacheKey = $this->treeDataCacheKey();

        if ($this->cachedTreeData !== null && $this->cachedTreeDataKey === $cacheKey) {
            return $this->cachedTreeData;
        }

        $this->cachedTreeData = $buildBrowseTreeAction->execute(
            project: $this->project,
            user: auth()->user(),
            scope: $this->scope,
            expandedNodeKeys: $this->expandedNodeKeys,
            alwaysShowFiles: $this->alwaysShowFiles,
            directoriesWithVisibleFiles: $this->directoriesWithVisibleFiles,
            focusedProjectId: $this->focusedProjectId,
        );

        $this->cachedTreeDataKey = $cacheKey;

        return $this->cachedTreeData;
    }

    private function treeDataCacheKey(): string
    {
        return md5(json_encode([
            'projectId' => $this->project?->id,
            'scope' => $this->scope,
            'expandedNodeKeys' => $this->expandedNodeKeys,
            'alwaysShowFiles' => $this->alwaysShowFiles,
            'directoriesWithVisibleFiles' => $this->directoriesWithVisibleFiles,
            'focusedProjectId' => $this->focusedProjectId,
        ]));
    }

    private function forgetTreeDataCache(): void
    {
        $this->cachedTreeData = null;
        $this->cachedTreeDataKey = null;
    }

    private function filterState(): array
    {
        return [
            'search' => $this->search,
            'selectedTypes' => $this->selectedTypes,
            'dateFilter' => $this->dateFilter,
            'experimentFilter' => $this->experimentFilter,
            'selectedTags' => $this->selectedTags,
        ];
    }

    private function findNodeByKey(array $nodes, string $key): ?array
    {
        foreach ($nodes as $node) {
            if ($node['key'] === $key) {
                return $node;
            }

            $found = $this->findNodeByKey($node['children'] ?? [], $key);

            if ($found !== null) {
                return $found;
            }
        }

        return null;
    }

    private function collectFolderKeys(array $nodes): array
    {
        $keys = [];

        foreach ($nodes as $node) {
            if (!empty($node['children'])) {
                $keys[] = $node['key'];
                $keys = [...$keys, ...$this->collectFolderKeys($node['children'])];
            }
        }

        return $keys;
    }
}
