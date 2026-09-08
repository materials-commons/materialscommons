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
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use function auth;
use function blank;
use function collect;
use function config;
use function in_array;
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

    private ?User $user = null;

    private ?Collection $listedInDatasets = null;

    private ?Collection $communities = null;

    private ?Collection $deletedProjects = null;

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
        $user = $this->currentUser();
        $projects = $this->userProjects();
        $datasets = $this->userDatasets();
        $listedInDatasets = $this->listedInDatasets();

        $this->projectsCount = $projects->count();
        $this->archivedCount = $this->userArchivedProjects()->count();
        $this->deletedCount = Project::getDeletedTrashCountForUser($user->id);
        $this->datasetsCount = $datasets->count();
        $this->publishedDatasetsCount = $datasets
            ->filter(fn($dataset) => $dataset->published_at !== null)
            ->count();

        $this->papersCount = $datasets
            ->flatMap(fn($dataset) => collect($dataset->papers ?? collect()))
            ->unique('id')
            ->count();

        $this->tagCount = $datasets
            ->merge($listedInDatasets)
            ->flatMap(fn($dataset) => collect($dataset->tags ?? collect())->pluck('name'))
            ->filter()
            ->unique()
            ->count();

        $this->communitiesCount = $this->userCommunities()->count();

        $datasetCollaboratorCount = $datasets
            ->flatMap(fn($dataset) => collect($dataset->ds_authors ?? collect()))
            ->pluck('name')
            ->filter()
            ->reject(fn($name) => strcasecmp(trim($name), (string) $user->name) === 0)
            ->map(fn($name) => mb_strtolower(trim($name)))
            ->unique()
            ->count();

        $projectCollaboratorCount = $projects
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
        $user = $this->currentUser();

        $this->tabData = match ($this->tab) {
            'projects' => $this->loadProjectsTabData($user),
            'datasets', 'licenses', 'papers', 'collaborators' => $this->loadDatasetBackedTabData(),
            'tags' => $this->loadTagsTabData(),
            'communities' => $this->loadCommunitiesTabData(),
            default => [],
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function loadProjectsTabData(User $user): array
    {
        $projects = $this->userProjects();

        return [
            'projects'                 => $projects,
            'activeProjects'           => $this->getActiveProjects($user, $projects),
            'recentlyAccessedProjects' => $this->getRecentlyAccessedProjects($user, $projects),
            'archivedProjects'         => $this->userArchivedProjects(),
            'deletedProjects'          => $this->deletedProjects(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function loadDatasetBackedTabData(): array
    {
        return [
            'projects' => $this->userProjects(),
            'datasets' => $this->userDatasets(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function loadTagsTabData(): array
    {
        return [
            'datasets'         => $this->userDatasets(),
            'listedInDatasets' => $this->listedInDatasets(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function loadCommunitiesTabData(): array
    {
        return [
            'communities'      => $this->userCommunities(),
            'datasets'         => $this->userDatasets(),
            'listedInDatasets' => $this->listedInDatasets(),
        ];
    }

    private function currentUser(): User
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $this->user = auth()->user();

        return $this->user;
    }

    private function userProjects(): Collection
    {
        if (isset($this->projects)) {
            return $this->projects;
        }

        $this->projects = $this->getUserProjects($this->currentUser()->id);

        return $this->projects;
    }

    private function userArchivedProjects(): Collection
    {
        if (isset($this->archivedProjects)) {
            return $this->archivedProjects;
        }

        $this->archivedProjects = $this->getUserArchivedProjects($this->currentUser()->id);

        return $this->archivedProjects;
    }

    private function userDatasets(): Collection
    {
        if (isset($this->datasets)) {
            return $this->datasets;
        }

        $this->datasets = $this->getUserDatasets($this->currentUser(), $this->userProjects());

        return $this->datasets;
    }

    private function listedInDatasets(): Collection
    {
        if (!is_null($this->listedInDatasets)) {
            return $this->listedInDatasets;
        }

        $this->listedInDatasets = $this->getDatasetsUserIsListedIn(
            $this->currentUser(),
            $this->userDatasets()
        );

        return $this->listedInDatasets;
    }

    private function userCommunities(): Collection
    {
        if (!is_null($this->communities)) {
            return $this->communities;
        }

        $this->communities = $this->getUserCommunities(
            $this->currentUser(),
            $this->userDatasets(),
            $this->listedInDatasets()
        );

        return $this->communities;
    }

    private function deletedProjects(): Collection
    {
        if (!is_null($this->deletedProjects)) {
            return $this->deletedProjects;
        }

        $this->deletedProjects = Project::getDeletedForUser($this->currentUser()->id);

        return $this->deletedProjects;
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
        $communityIdsFromDatasets = $this->getPublishedCommunityIdsForDatasets(
            $datasets->merge($listedInDatasets)->pluck('id')
        );

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
                                      $query->orWhereIn('id', $communityIdsFromDatasets);
                                  });
                        })
                        ->orderBy('name')
                        ->get();
    }

    private function getPublishedCommunityIdsForDatasets(Collection $datasetIds): Collection
    {
        $datasetIds = $datasetIds
            ->filter()
            ->unique()
            ->values();

        if ($datasetIds->isEmpty()) {
            return collect();
        }

        return DB::table('dataset2community')
                 ->join('communities', 'communities.id', '=', 'dataset2community.community_id')
                 ->whereIn('dataset2community.dataset_id', $datasetIds)
                 ->where('communities.public', true)
                 ->distinct()
                 ->pluck('dataset2community.community_id')
                 ->values();
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
        $user = $this->currentUser();

        return view('livewire.dashboard.my-research.tabs', [
            'user'           => $user,
            'hasAffiliation' => !blank($user->affiliations ?? null),
            'hasOrcid'       => !blank($user->orcid ?? null),
        ]);
    }
}
