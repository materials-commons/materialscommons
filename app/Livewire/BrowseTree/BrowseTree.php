<?php

namespace App\Livewire\BrowseTree;

use App\Actions\BrowseTree\BuildBrowseTreeAction;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use function auth;
use function in_array;
use function view;

class BrowseTree extends Component
{
    public ?Project $project = null;

    #[Url(as: 'scope')]
    public string $scope = 'project';

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'types')]
    public array $selectedTypes = [
        'sample',
        'computation',
        'file',
        'dataset',
        'experiment',
    ];

    #[Url(as: 'group')]
    public string $groupBy = 'project';

    #[Url(as: 'date')]
    public string $dateFilter = 'any';

    #[Url(as: 'experiment')]
    public string $experimentFilter = 'any';

    #[Url(as: 'tags')]
    public array $selectedTags = [];

    #[Url(as: 'showFiles')]
    public bool $alwaysShowFiles = false;

    public string $quickFilter = 'all';

    public array $expandedNodeKeys = [];

    public array $directoriesWithVisibleFiles = [];

    public ?array $selectedItem = null;

    public function mount(?Project $project = null, string $defaultScope = 'project'): void
    {
        $this->project = $project;
        $this->scope = $project === null ? 'all' : $defaultScope;

        $this->expandedNodeKeys = $this->scope === 'all'
            ? []
            : ['current-project'];
    }

    public function render(BuildBrowseTreeAction $buildBrowseTreeAction): View
    {
        $baseTree = $this->treeData($buildBrowseTreeAction);
        $tree = $this->filteredTree($buildBrowseTreeAction);

        return view('livewire.browse-tree.browse-tree', [
            'tree' => $tree,
            'visibleLeafCount' => $this->countLeaves($tree),
            'typeCounts' => $this->typeCounts($baseTree),
            'availableTags' => $this->availableTags($baseTree),
            'availableExperiments' => $this->availableExperiments($baseTree),
        ]);
    }

    public function setScope(string $scope): void
    {
        if (!in_array($scope, ['project', 'all'], true)) {
            return;
        }

        $this->scope = $scope;
        $this->selectedItem = null;

        if ($scope === 'all') {
            $this->expandedNodeKeys = [];
        } else {
            $this->expandedNodeKeys = array_values(array_unique([
                ...$this->expandedNodeKeys,
                'current-project',
            ]));
        }
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
    }

    public function setDateFilter(string $dateFilter): void
    {
        if (!in_array($dateFilter, ['any', 'today', 'last-7-days', 'last-30-days', 'this-year'], true)) {
            return;
        }

        $this->dateFilter = $dateFilter;
        $this->expandMatchingParents();
    }

    public function setExperimentFilter(string $experimentFilter): void
    {
        $this->experimentFilter = $experimentFilter;
        $this->expandMatchingParents();
    }

    public function toggleTag(string $tag): void
    {
        if (in_array($tag, $this->selectedTags, true)) {
            $this->selectedTags = array_values(array_filter(
                $this->selectedTags,
                fn(string $selectedTag) => $selectedTag !== $tag
            ));

            return;
        }

        $this->selectedTags[] = $tag;
        $this->expandMatchingParents();
    }

    public function clearFacets(): void
    {
        $this->dateFilter = 'any';
        $this->experimentFilter = 'any';
        $this->selectedTags = [];
    }

    public function toggleNode(string $key): void
    {
        if (in_array($key, $this->expandedNodeKeys, true)) {
            $this->expandedNodeKeys = array_values(array_filter(
                $this->expandedNodeKeys,
                fn(string $expandedKey) => $expandedKey !== $key
            ));

            return;
        }

        $this->expandedNodeKeys[] = $key;
    }

    public function expandAll(): void
    {
        // With real data, expand all can be extremely expensive.
        // For now, expand only the current visible top-level folders.
        $buildBrowseTreeAction = app(BuildBrowseTreeAction::class);

        $tree = $this->treeData($buildBrowseTreeAction);

        $this->expandedNodeKeys = array_values(array_unique([
            ...$this->expandedNodeKeys,
            ...collect($tree)->pluck('key')->all(),
        ]));
    }

    public function collapseAll(): void
    {
        $this->expandedNodeKeys = [];
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->quickFilter = 'all';
    }

    public function selectItem(string $key): void
    {
        $buildBrowseTreeAction = app(BuildBrowseTreeAction::class);

        $this->selectedItem = $this->findNodeByKey(
            $this->treeData($buildBrowseTreeAction),
            $key
        );
    }

    public function setSuggestedSearch(string $search): void
    {
        $this->search = $search;
        $this->quickFilter = 'all';
        $this->expandMatchingParents();
    }

    public function toggleDirectoryFiles(string $directoryKey): void
    {
        if (in_array($directoryKey, $this->directoriesWithVisibleFiles, true)) {
            $this->directoriesWithVisibleFiles = array_values(array_filter(
                $this->directoriesWithVisibleFiles,
                fn(string $visibleDirectoryKey) => $visibleDirectoryKey !== $directoryKey
            ));

            return;
        }

        $this->directoriesWithVisibleFiles[] = $directoryKey;
    }

    public function updatedAlwaysShowFiles(): void
    {
        if ($this->alwaysShowFiles) {
            return;
        }

        $this->directoriesWithVisibleFiles = [];
    }

    public function updatedSearch(): void
    {
        $this->quickFilter = 'all';

        if ($this->search !== '') {
            $this->expandMatchingParents();
        }
    }

    public function updatedSelectedTypes(): void
    {
        $this->expandMatchingParents();
    }

    public function updatedGroupBy(): void
    {
        if (!in_array($this->groupBy, ['project', 'type'], true)) {
            $this->groupBy = 'project';
        }

        $this->selectedItem = null;

        $buildBrowseTreeAction = app(BuildBrowseTreeAction::class);

        $this->expandedNodeKeys = array_values(array_unique([
            ...$this->expandedNodeKeys,
            ...array_slice($this->collectFolderKeys($this->groupedTree($this->treeData($buildBrowseTreeAction))), 0, 8),
        ]));
    }

    private function filteredTree(BuildBrowseTreeAction $buildBrowseTreeAction): array
    {
        return $this->filterNodes(
            $this->groupedTree($this->treeData($buildBrowseTreeAction))
        );
    }

    private function treeData(BuildBrowseTreeAction $buildBrowseTreeAction): array
    {
        return $buildBrowseTreeAction->execute(
            $this->project,
            auth()->user(),
            $this->scope,
            $this->expandedNodeKeys,
            $this->alwaysShowFiles,
            $this->directoriesWithVisibleFiles
        );
    }

    private function groupedTree(array $tree): array
    {
        if ($this->groupBy === 'type') {
            return $this->groupTreeByType($tree);
        }

        return $tree;
    }

    private function groupTreeByType(array $tree): array
    {
        $leaves = collect($this->flattenLeaves($tree));

        $typeDefinitions = [
            'sample' => [
                'title' => 'Samples',
                'icon' => 'fas fa-vials text-success',
            ],
            'computation' => [
                'title' => 'Computations',
                'icon' => 'fas fa-cogs text-primary',
            ],
            'file' => [
                'title' => 'Files',
                'icon' => 'fas fa-file-alt text-secondary',
            ],
            'dataset' => [
                'title' => 'Datasets',
                'icon' => 'fas fa-database text-info',
            ],
            'experiment' => [
                'title' => 'Experiments',
                'icon' => 'fas fa-flask text-purple',
            ],
        ];

        return collect($typeDefinitions)
            ->map(function (array $definition, string $type) use ($leaves) {
                $itemsOfType = $leaves->where('type', $type)->values();

                $projectGroups = $itemsOfType
                    ->groupBy(fn(array $item) => $item['project'] ?? 'No Project')
                    ->map(function (Collection $projectItems, string $projectName) use ($type) {
                        return [
                            'key' => 'group-type-'.$type.'-project-'.str($projectName)->slug(),
                            'kind' => 'folder',
                            'type' => 'folder',
                            'title' => $projectName,
                            'icon' => 'fas fa-folder text-warning',
                            'count' => $projectItems->count(),
                            'searchTerms' => [$projectName, $type],
                            'children' => $projectItems->values()->all(),
                        ];
                    })
                    ->values()
                    ->all();

                return [
                    'key' => 'group-type-'.$type,
                    'kind' => 'folder',
                    'type' => 'folder',
                    'title' => $definition['title'],
                    'icon' => $definition['icon'],
                    'count' => $itemsOfType->count(),
                    'searchTerms' => [$type, $definition['title']],
                    'children' => $projectGroups,
                ];
            })
            ->filter(fn(array $node) => count($node['children']) > 0)
            ->values()
            ->all();
    }

    private function flattenLeaves(array $nodes): array
    {
        $leaves = [];

        foreach ($nodes as $node) {
            if (in_array(($node['kind'] ?? null), ['action', 'message'], true)) {
                continue;
            }

            if (empty($node['children'])) {
                if (($node['kind'] ?? 'folder') !== 'folder') {
                    $leaves[] = $node;
                }

                continue;
            }

            $leaves = [...$leaves, ...$this->flattenLeaves($node['children'])];
        }

        return $leaves;
    }

    private function filterNodes(array $nodes): array
    {
        $filtered = [];

        foreach ($nodes as $node) {
            $children = $node['children'] ?? [];
            $filteredChildren = $this->filterNodes($children);

            $isLeaf = empty($children);
            $matches = $this->nodeMatches($node);

            if (($isLeaf && $matches) || (!$isLeaf && ($matches || count($filteredChildren) > 0))) {
                $node['children'] = $filteredChildren;
                $filtered[] = $node;
            }
        }

        return $filtered;
    }

    private function nodeMatches(array $node): bool
    {
        if (in_array(($node['kind'] ?? null), ['action', 'message'], true)) {
            return true;
        }

        if (($node['kind'] ?? 'folder') !== 'folder' && !in_array($node['type'], $this->selectedTypes, true)) {
            return false;
        }

        if (!$this->nodeMatchesDate($node)) {
            return false;
        }

        if (!$this->nodeMatchesExperiment($node)) {
            return false;
        }

        if (!$this->nodeMatchesTags($node)) {
            return false;
        }

        $search = trim(mb_strtolower($this->search));

        if ($search === '') {
            return true;
        }

        $haystack = mb_strtolower(implode(' ', [
            $node['title'] ?? '',
            $node['type'] ?? '',
            $node['project'] ?? '',
            $node['location'] ?? '',
            $node['description'] ?? '',
            $node['dateLabel'] ?? '',
            $node['experiment'] ?? '',
            implode(' ', $node['tags'] ?? []),
            implode(' ', $node['searchTerms'] ?? []),
        ]));

        foreach (preg_split('/\s+/', $search) as $term) {
            if ($term !== '' && !str_contains($haystack, $term)) {
                return false;
            }
        }

        return true;
    }

    private function nodeMatchesDate(array $node): bool
    {
        if ($this->dateFilter === 'any' || ($node['kind'] ?? 'folder') === 'folder') {
            return true;
        }

        return ($node['dateBucket'] ?? null) === $this->dateFilter;
    }

    private function nodeMatchesExperiment(array $node): bool
    {
        if ($this->experimentFilter === 'any' || ($node['kind'] ?? 'folder') === 'folder') {
            return true;
        }

        return ($node['experiment'] ?? null) === $this->experimentFilter;
    }

    private function nodeMatchesTags(array $node): bool
    {
        if (count($this->selectedTags) === 0 || ($node['kind'] ?? 'folder') === 'folder') {
            return true;
        }

        $nodeTags = collect($node['tags'] ?? [])
            ->map(fn(string $tag) => mb_strtolower($tag))
            ->all();

        foreach ($this->selectedTags as $selectedTag) {
            if (!in_array(mb_strtolower($selectedTag), $nodeTags, true)) {
                return false;
            }
        }

        return true;
    }

    private function expandMatchingParents(): void
    {
        $buildBrowseTreeAction = app(BuildBrowseTreeAction::class);
        $expanded = [];

        $walk = function (array $nodes, array $parents = []) use (&$walk, &$expanded): bool {
            $hasMatch = false;

            foreach ($nodes as $node) {
                $childHasMatch = $walk($node['children'] ?? [], [...$parents, $node['key']]);
                $nodeMatches = $this->nodeMatches($node);

                if ($nodeMatches || $childHasMatch) {
                    $hasMatch = true;
                    $expanded = [...$expanded, ...$parents, $node['key']];
                }
            }

            return $hasMatch;
        };

        $walk($this->groupedTree($this->treeData($buildBrowseTreeAction)));

        $this->expandedNodeKeys = array_values(array_unique($expanded));
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

    private function countLeaves(array $nodes): int
    {
        $count = 0;

        foreach ($nodes as $node) {
            if (in_array(($node['kind'] ?? null), ['action', 'message'], true)) {
                continue;
            }

            if (empty($node['children'])) {
                $count++;
            } else {
                $count += $this->countLeaves($node['children']);
            }
        }

        return $count;
    }

    private function typeCounts(array $nodes): array
    {
        $counts = [
            'sample' => 0,
            'computation' => 0,
            'file' => 0,
            'dataset' => 0,
            'experiment' => 0,
        ];

        foreach ($this->flattenLeaves($nodes) as $item) {
            if (isset($counts[$item['type']])) {
                $counts[$item['type']]++;
            }
        }

        return $counts;
    }

    private function availableTags(array $nodes): array
    {
        return collect($this->flattenLeaves($nodes))
            ->flatMap(fn(array $node) => $node['tags'] ?? [])
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    private function availableExperiments(array $nodes): array
    {
        return collect($this->flattenLeaves($nodes))
            ->pluck('experiment')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }
}
