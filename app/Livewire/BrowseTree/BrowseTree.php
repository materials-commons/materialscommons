<?php

namespace App\Livewire\BrowseTree;

use App\Actions\BrowseTree\BuildBrowseTreeAction;
use App\Actions\BrowseTree\Support\BrowseTreeFilter;
use App\Actions\BrowseTree\Support\BrowseTreeGrouper;
use App\Actions\BrowseTree\Support\BrowseTreeMetrics;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use function auth;
use function in_array;
use function str_starts_with;
use function view;

class BrowseTree extends Component
{
    public ?Project $project = null;

    public ?int $focusedProjectId = null;

    public ?string $focusedProjectName = null;

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

        if ($project !== null) {
            $this->focusedProjectId = $project->id;
            $this->focusedProjectName = $project->name;
        }

        $this->scope = $project === null ? 'all' : $defaultScope;

        $this->expandedNodeKeys = $this->scope === 'all'
            ? []
            : ['current-project'];
    }

    public function render(
        BuildBrowseTreeAction $buildBrowseTreeAction,
        BrowseTreeFilter $filter,
        BrowseTreeGrouper $grouper,
        BrowseTreeMetrics $metrics,
    ): View {
        $baseTree = $this->treeData($buildBrowseTreeAction);
        $groupedTree = $this->groupBy === 'type' ? $grouper->byType($baseTree) : $baseTree;
        $tree = $filter->filter($groupedTree, $this->filterState());

        return view('livewire.browse-tree.browse-tree', [
            'tree' => $tree,
            'visibleLeafCount' => $metrics->countLeaves($tree),
            'typeCounts' => $metrics->typeCounts($baseTree),
            'availableTags' => $metrics->availableTags($baseTree),
            'availableExperiments' => $metrics->availableExperiments($baseTree),
        ]);
    }

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
            return;
        }

        $this->scope = $scope;
        $this->selectedItem = null;

        if ($scope === 'all') {
            $this->expandedNodeKeys = [];
            return;
        }

        $this->expandedNodeKeys = ['current-project'];
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
        if (str_starts_with($key, 'project-')) {
            $this->rememberFocusedProjectFromNodeKey($key);
        }

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
        $grouper = app(BrowseTreeGrouper::class);
        $tree = $this->treeData($buildBrowseTreeAction);
        $groupedTree = $this->groupBy === 'type' ? $grouper->byType($tree) : $tree;

        $this->expandedNodeKeys = array_values(array_unique([
            ...$this->expandedNodeKeys,
            ...array_slice($this->collectFolderKeys($groupedTree), 0, 8),
        ]));
    }

    private function treeData(BuildBrowseTreeAction $buildBrowseTreeAction): array
    {
        return $buildBrowseTreeAction->execute(
            project: $this->project,
            user: auth()->user(),
            scope: $this->scope,
            expandedNodeKeys: $this->expandedNodeKeys,
            alwaysShowFiles: $this->alwaysShowFiles,
            directoriesWithVisibleFiles: $this->directoriesWithVisibleFiles,
            focusedProjectId: $this->focusedProjectId,
        );
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
