<?php

namespace App\Livewire\BrowseTree;

use App\Actions\BrowseTree\BuildBrowseTreeAction;
use App\Actions\BrowseTree\Support\BrowseTreeFilter;
use App\Actions\BrowseTree\Support\BrowseTreeGrouper;
use App\Actions\BrowseTree\Support\BrowseTreeMetrics;
use App\Livewire\BrowseTree\Concerns\HasBrowseTreePersistence;
use App\Livewire\BrowseTree\Concerns\HasBrowseTreeState;
use App\Livewire\BrowseTree\Concerns\InteractsWithBrowseTree;
use App\Livewire\BrowseTree\Concerns\ValidatesBrowseTreeState;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use function view;

class BrowseTree extends Component
{
    use ValidatesBrowseTreeState;
    use HasBrowseTreeState;
    use HasBrowseTreePersistence;
    use InteractsWithBrowseTree;

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

    public ?string $selectedItemKey = null;

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
}
