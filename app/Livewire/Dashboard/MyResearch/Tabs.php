<?php

namespace App\Livewire\Dashboard\MyResearch;

use App\Models\Community;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;
use App\Traits\Projects\UserProjects;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use function auth;
use function blank;
use function collect;
use function config;
use function is_null;
use function mb_strtolower;
use function now;
use function strcasecmp;
use function trim;

class Tabs extends Component
{
    use UserProjects;

    #[Url(as: 'myResearchTab')]
    public string $tab = 'overview';

    public int $projectsCount = 0;

    public Collection $projects;

    public Collection $datasets;

    public Collection $archivedProjects;

    public int $datasetsCount = 0;

    public int $papersCount = 0;

    public int $tagCount = 0;

    public int $communitiesCount = 0;

    public int $collaboratorsCount = 0;

    public int $archivedCount = 0;

    public int $publishedDatasetsCount = 0;

    public int $deletedCount = 0;

    /**
     * @var array<string, mixed>
     */
    public array $tabData = [];

    /**
     * @var array<int, string>
     */
    private array $allowedTabs = [
        'overview',
        'projects',
        'datasets',
        'licenses',
        'papers',
        'collaborators',
        'tags',
        'communities',
        'metadata',
    ];

    public function mount(): void
    {
        if (!in_array($this->tab, $this->allowedTabs, true)) {
            $this->tab = 'overview';
        }

        $this->loadTabCounts();
        $this->loadTabData();
    }

    public function restoreTab(?string $tab): void
    {
        if ($tab === null || !in_array($tab, $this->allowedTabs, true)) {
            return;
        }

        if ($this->tab === $tab) {
            return;
        }

        $this->tab = $tab;
        $this->loadTabData();
    }

    public function setTab(string $tab): void
    {
        if (!in_array($tab, $this->allowedTabs, true)) {
            return;
        }

        if ($this->tab === $tab) {
            return;
        }

        $this->tab = $tab;
        $this->loadTabData();

        $this->dispatch('dashboard-my-research-tab-changed', tab: $tab);
    }

    private function loadTabCounts(): void
    {
        $user = auth()->user();
        $this->projects = $this->getUserProjects($user->id);
        $projects = $this->projects;
        $datasets = $this->getUserDatasets($user, $this->projects);
        $this->datasets = $datasets;
        $listedInDatasets = $this->getDatasetsUserIsListedIn($user, $datasets);

        $this->projectsCount = $projects->count();
        $this->archivedProjects = $this->getUserArchivedProjects($user->id);
        $this->archivedCount = $this->archivedProjects->count();
        $this->deletedCount = Project::getDeletedTrashCountForUser($user->id);
        $this->datasetsCount = $datasets->count();
        $this->publishedDatasetsCount = $datasets
            ->filter(fn($dataset) => $dataset->published_at !== null)
            ->count();

        $this->papersCount = collect($datasets)
            ->flatMap(fn($dataset) => collect($dataset->papers ?? collect()))
            ->unique('id')
            ->count();

        $this->tagCount = collect($datasets)
            ->merge(collect($listedInDatasets))
            ->flatMap(fn($dataset) => collect($dataset->tags ?? collect())->pluck('name'))
            ->filter()
            ->unique()
            ->count();

        $this->communitiesCount = $this->getUserCommunities($user, $datasets, $listedInDatasets)->count();

        $datasetCollaboratorCount = collect($datasets)
            ->flatMap(fn($dataset) => collect($dataset->ds_authors ?? collect()))
            ->pluck('name')
            ->filter()
            ->reject(fn($name) => strcasecmp(trim($name), (string) $user->name) === 0)
            ->map(fn($name) => mb_strtolower(trim($name)))
            ->unique()
            ->count();

        $projectCollaboratorCount = collect($projects)
            ->flatMap(function ($project) use ($user) {
                return collect($project->team?->members ?? collect())
                    ->merge(collect($project->team?->admins ?? collect()))
                    ->filter(fn($member) => (int) ($member->id ?? 0) !== (int) $user->id)
                    ->map(fn($member) => 'user:' . $member->id);
            })
            ->unique()
            ->count();

        $this->collaboratorsCount = $datasetCollaboratorCount + $projectCollaboratorCount;
    }

    private function loadTabData(): void
    {
        $user = auth()->user();

        $this->tabData = match ($this->tab) {
            'projects' => $this->loadProjectsTabData($user),
            'datasets', 'licenses', 'papers', 'collaborators' => $this->loadDatasetBackedTabData($user),
            'tags' => $this->loadTagsTabData($user),
            'communities' => $this->loadCommunitiesTabData($user),
            default => [],
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function loadProjectsTabData(User $user): array
    {
        $projects = $this->getUserProjects($user->id);

        return [
            'projects' => $projects,
            'activeProjects' => $this->getActiveProjects($user, $projects),
            'recentlyAccessedProjects' => $this->getRecentlyAccessedProjects($user, $projects),
            'archivedProjects' => $this->getUserArchivedProjects($user->id),
            'deletedProjects' => Project::getDeletedForUser($user->id),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function loadDatasetBackedTabData(User $user): array
    {
        $projects = $this->getUserProjects($user->id);
        $datasets = $this->getUserDatasets($user, $projects);

        return [
            'projects' => $projects,
            'datasets' => $datasets,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function loadTagsTabData(User $user): array
    {
        $projects = $this->getUserProjects($user->id);
        $datasets = $this->getUserDatasets($user, $projects);

        return [
            'datasets' => $datasets,
            'listedInDatasets' => $this->getDatasetsUserIsListedIn($user, $datasets),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function loadCommunitiesTabData(User $user): array
    {
        $projects = $this->getUserProjects($user->id);
        $datasets = $this->getUserDatasets($user, $projects);
        $listedInDatasets = $this->getDatasetsUserIsListedIn($user, $datasets);

        return [
            'communities' => $this->getUserCommunities($user, $datasets, $listedInDatasets),
            'datasets' => $datasets,
            'listedInDatasets' => $listedInDatasets,
        ];
    }

    private function getUserDatasets(User $user, Collection $projects): Collection
    {
        $projectIds = $projects->pluck('id');

        return Dataset::query()
                      ->with(['project', 'papers.owner', 'tags'])
                      ->withCount(['views', 'downloads', 'comments'])
                      ->withCounts()
                      ->where(function ($query) use ($user, $projectIds) {
                          $query->where('owner_id', $user->id)
                                ->orWhereIn('project_id', $projectIds);
                      })
                      ->whereDoesntHave('tags', function ($query) {
                          $query->where('tags.id', config('visus.import_tag_id'));
                      })
                      ->get();
    }

    private function getUserCommunities(User $user, Collection $datasets, Collection $listedInDatasets): Collection
    {
        $communityIdsFromDatasets = collect($datasets)
            ->merge(collect($listedInDatasets))
            ->flatMap(fn($dataset) => collect($dataset->publishedCommunities ?? collect())->pluck('id'))
            ->filter();

        return Community::query()
                        ->with([
                            'owner',
                            'links',
                            'files',
                            'publishedDatasets' => function ($query) {
                                $query->with(['owner', 'project', 'tags'])
                                      ->withCount(['views', 'downloads'])
                                      ->orderByDesc('published_at');
                            },
                        ])
                        ->where(function ($query) use ($user, $communityIdsFromDatasets) {
                            $query->where('owner_id', $user->id)
                                  ->when($communityIdsFromDatasets->isNotEmpty(), function ($query) use ($communityIdsFromDatasets) {
                                      $query->orWhereIn('id', $communityIdsFromDatasets->unique()->values());
                                  });
                        })
                        ->orderBy('name')
                        ->get();
    }

    private function getDatasetsUserIsListedIn(User $user, Collection $datasets): Collection
    {
        $existingDatasetIds = $datasets->pluck('id');

        return Dataset::query()
                      ->with(['owner', 'project', 'tags'])
                      ->withCount(['views', 'downloads'])
                      ->whereNotNull('published_at')
                      ->where('owner_id', '!=', $user->id)
                      ->when($existingDatasetIds->isNotEmpty(), function ($query) use ($existingDatasetIds) {
                          $query->whereNotIn('id', $existingDatasetIds);
                      })
                      ->whereRaw('ds_authors COLLATE utf8mb4_general_ci like ?', ['%"name":"' . $user->name . '"%'])
                      ->whereDoesntHave('tags', function ($query) {
                          $query->where('tags.id', config('visus.import_tag_id'));
                      })
                      ->orderByDesc('published_at')
                      ->get();
    }

    private function getActiveProjects(User $user, Collection $projects): Collection
    {
        if (!$user->hasActiveProjects()) {
            return collect();
        }

        return $projects->filter(function ($project) use ($user) {
            return $user->isActiveProject($project);
        })->sortByDesc(function ($project) use ($user) {
            $accessedOn = $user->projectRecentlyAccessedOn($project);

            if (is_null($accessedOn)) {
                return now()->getTimestamp();
            }

            return Carbon::parse($accessedOn)->getTimestamp();
        });
    }

    private function getRecentlyAccessedProjects(User $user, Collection $projects): Collection
    {
        if (!$user->hasRecentlyAccessedProjectsThatAreNotActive()) {
            return collect();
        }

        return $projects->filter(function ($project) use ($user) {
            if ($user->isActiveProject($project)) {
                return false;
            }

            if (is_null($user->projectRecentlyAccessedOn($project))) {
                return false;
            }

            return true;
        })->sortByDesc(function ($project) use ($user) {
            $accessedOn = $user->projectRecentlyAccessedOn($project);

            if (is_null($accessedOn)) {
                return now()->getTimestamp();
            }

            return Carbon::parse($accessedOn)->getTimestamp();
        });
    }

    public function render(): View
    {
        return view('livewire.dashboard.my-research.tabs', [
            'user' => auth()->user(),
            'hasAffiliation' => !blank(auth()->user()->affiliations ?? null),
            'hasOrcid' => !blank(auth()->user()->orcid ?? null),
        ]);
    }
}
