<?php

namespace App\View\Components\Dashboard\MyResearch;

use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class NeedsAttention extends Component
{
    public int $totalCount;

    public array $items;

    public bool $hasItems;

    private User $user;

    private int $detailsLimit = 10;

    private ?Collection $attentionDatasets = null;

    private ?Collection $datasetIdsUserIsPartOf = null;

    private ?Collection $projectIds = null;

    public function __construct()
    {
        $this->user = auth()->user();
        $this->items = $this->buildItems();
        $this->totalCount = collect($this->items)->sum('count');
        $this->hasItems = $this->totalCount > 0;
    }

    public function render(): View
    {
        return view('components.dashboard.my-research.needs-attention');
    }

    private function buildItems(): array
    {
        return collect([
            $this->missingLicenseItem(),
            $this->missingAuthorsItem(),
            $this->missingDescriptionItem(),
            $this->publishedDatasetsMissingTagsItem(),
            $this->draftsWithoutFilesItem(),
            $this->unpublishedDatasetsOlderThanThreeMonthsItem(),
            $this->projectsMissingReadmeOrDescriptionItem(),
            $this->projectsWithoutDatasetsItem(),
        ])
            ->filter()
            ->sortByDesc('priority')
            ->values()
            ->toArray();
    }

    private function missingLicenseItem(): ?array
    {
        $datasets = $this->attentionDatasets()
                         ->filter(fn(Dataset $dataset) => blank($dataset->license))
                         ->sortBy('name')
                         ->values();

        return $this->datasetAttentionItem(
            $datasets,
            'missing-licenses',
            'Datasets missing licenses',
            'Add license information so datasets are ready for publication and reuse.',
            'fas fa-balance-scale',
            'text-bg-danger',
            100,
            'Review licenses',
            null,
            'Datasets missing licenses',
            'Missing license'
        );
    }

    private function missingAuthorsItem(): ?array
    {
        $datasets = $this->attentionDatasets()
                         ->filter(fn(Dataset $dataset) => blank($dataset->ds_authors))
                         ->sortBy('name')
                         ->values();

        return $this->datasetAttentionItem(
            $datasets,
            'missing-authors',
            'Datasets missing authors',
            'Add dataset authors so attribution is complete before publishing or sharing.',
            'fas fa-user-edit',
            'text-bg-danger',
            98,
            'Review authors',
            null,
            'Datasets missing authors',
            'Missing authors'
        );
    }

    private function missingDescriptionItem(): ?array
    {
        $datasets = $this->attentionDatasets()
                         ->filter(function (Dataset $dataset) {
                             return blank($dataset->description) || blank($dataset->summary);
                         })
                         ->sortBy('name')
                         ->values();

        return $this->datasetAttentionItem(
            $datasets,
            'missing-descriptions',
            'Datasets missing descriptions',
            'Complete summaries and descriptions to improve discoverability and publication readiness.',
            'fas fa-align-left',
            'text-bg-warning',
            90,
            'Review metadata',
            null,
            'Datasets missing descriptions',
            'Missing summary or description'
        );
    }

    private function publishedDatasetsMissingTagsItem(): ?array
    {
        $datasets = $this->attentionDatasets()
                         ->filter(function (Dataset $dataset) {
                             return !is_null($dataset->published_at) && $dataset->tags->isEmpty();
                         })
                         ->sortBy('name')
                         ->values();

        return $this->datasetAttentionItem(
            $datasets,
            'published-missing-tags',
            'Published datasets missing tags',
            'Add tags to published datasets to improve search, browsing, and discoverability.',
            'fas fa-tags',
            'text-bg-success',
            88,
            'Review tags',
            null,
            'Published datasets missing tags',
            'Published without tags'
        );
    }

    private function draftsWithoutFilesItem(): ?array
    {
        $datasets = $this->attentionDatasets()
                         ->filter(function (Dataset $dataset) {
                             return is_null($dataset->published_at) && !$this->datasetHasSelectedFiles($dataset);
                         })
                         ->sortBy('name')
                         ->values();

        return $this->datasetAttentionItem(
            $datasets,
            'drafts-without-files',
            'Draft datasets without selected files',
            'Select files for draft datasets before publishing or sharing them.',
            'fas fa-file-circle-question',
            'text-bg-info',
            80,
            'Review drafts',
            null,
            'Draft datasets without selected files',
            'No selected files'
        );
    }

    private function unpublishedDatasetsOlderThanThreeMonthsItem(): ?array
    {
        $cutoff = now()->subMonths(3);

        $datasets = $this->attentionDatasets()
                         ->filter(function (Dataset $dataset) use ($cutoff) {
                             return is_null($dataset->published_at) && $dataset->updated_at < $cutoff;
                         })
                         ->sortBy('updated_at')
                         ->values();

        return $this->datasetAttentionItem(
            $datasets,
            'old-unpublished-datasets',
            'Unpublished datasets older than 3 months',
            'Review older unpublished datasets and decide whether they should be updated, published, or removed.',
            'fas fa-clock',
            'text-bg-secondary',
            75,
            'Review unpublished datasets',
            null,
            'Older unpublished datasets',
            'Last updated over 3 months ago'
        );
    }

    private function projectsMissingReadmeOrDescriptionItem(): ?array
    {
        $query = Project::whereIn('id', $this->projectIds())
                        ->whereNull('deleted_at')
                        ->whereNull('archived_at')
                        ->where(function (Builder $query) {
                            $query->where(function (Builder $query) {
                                $query->whereNull('description')
                                      ->orWhere('description', '');
                            })->orWhereDoesntHave('files', function (Builder $query) {
                                $query->whereRaw('lower(name) = ?', ['readme.md'])
                                      ->active();
                            });
                        });

        $count = (clone $query)->count();

        if ($count === 0) {
            return null;
        }

        $projects = (clone $query)
            ->with('rootDir')
            ->orderBy('name')
            ->limit($this->detailsLimit)
            ->get();

        return [
            'key'            => 'projects-missing-readme-description',
            'title'          => 'Projects missing README or description',
            'description'    => 'Add a project README and description so collaborators can understand the project context.',
            'count'          => $count,
            'icon'           => 'fas fa-book-open',
            'badgeClass'     => 'text-bg-warning',
            'priority'       => 72,
            'actionLabel'    => 'Review projects',
            'actionUrl'      => route('dashboard.projects.show'),
            'detailsLabel'   => 'Projects missing README or description',
            'details'        => $this->projectReadmeDescriptionDetails($projects),
            'remainingCount' => max($count - $projects->count(), 0),
        ];
    }

    private function projectsWithoutDatasetsItem(): ?array
    {
        $query = Project::whereIn('id', $this->projectIds())
                        ->whereNull('deleted_at')
                        ->whereNull('archived_at')
                        ->doesntHave('datasets');

        $count = (clone $query)->count();

        if ($count === 0) {
            return null;
        }

        $projects = (clone $query)
            ->orderBy('name')
            ->limit($this->detailsLimit)
            ->get();

        return [
            'key'            => 'projects-without-datasets',
            'title'          => 'Active projects without datasets',
            'description'    => 'Create datasets from active projects when the data is ready to organize, share, or publish.',
            'count'          => $count,
            'icon'           => 'fas fa-folder-open',
            'badgeClass'     => 'text-bg-primary',
            'priority'       => 60,
            'actionLabel'    => 'View projects',
            'actionUrl'      => route('dashboard.projects.show'),
            'detailsLabel'   => 'Active projects without datasets',
            'details'        => $this->projectDetails($projects, 'No datasets'),
            'remainingCount' => max($count - $projects->count(), 0),
        ];
    }

    private function datasetAttentionItem(
        Collection $datasets,
        string $key,
        string $title,
        string $description,
        string $icon,
        string $badgeClass,
        int $priority,
        string $actionLabel,
        ?string $actionUrl,
        string $detailsLabel,
        string $issue,
    ): ?array {
        $count = $datasets->count();

        if ($count === 0) {
            return null;
        }

        $visibleDatasets = $datasets->take($this->detailsLimit);

        return [
            'key'            => $key,
            'title'          => $title,
            'description'    => $description,
            'count'          => $count,
            'icon'           => $icon,
            'badgeClass'     => $badgeClass,
            'priority'       => $priority,
            'actionLabel'    => $actionLabel,
            'actionUrl'      => $actionUrl,
            'detailsLabel'   => $detailsLabel,
            'details'        => $this->datasetDetails($visibleDatasets, $issue),
            'remainingCount' => max($count - $visibleDatasets->count(), 0),
        ];
    }

    private function attentionDatasets(): Collection
    {
        if (!is_null($this->attentionDatasets)) {
            return $this->attentionDatasets;
        }

        $this->attentionDatasets = Dataset::query()
                                          ->with(['project', 'tags'])
                                          ->whereIn('id', $this->datasetIdsUserIsPartOf())
                                          ->get();

        return $this->attentionDatasets;
    }

    private function datasetIdsUserIsPartOf(): Collection
    {
        if (!is_null($this->datasetIdsUserIsPartOf)) {
            return $this->datasetIdsUserIsPartOf;
        }

        $ownedDatasetIds = Dataset::where('owner_id', $this->user->id)
                                  ->whereDoesntHave('tags', function ($q) {
                                      $q->where('tags.id', config('visus.import_tag_id'));
                                  })
                                  ->pluck('id');

        $linkedDatasetIds = $this->user
            ->datasets()
            ->pluck('datasets.id');

        $this->datasetIdsUserIsPartOf = $ownedDatasetIds
            ->merge($linkedDatasetIds)
            ->unique()
            ->values();

        return $this->datasetIdsUserIsPartOf;
    }

    private function projectIds(): Collection
    {
        if (!is_null($this->projectIds)) {
            return $this->projectIds;
        }

        $this->projectIds = $this->user
            ->projects()
            ->pluck('projects.id')
            ->unique()
            ->values();

        return $this->projectIds;
    }

    private function datasetDetails(Collection $datasets, string $issue): array
    {
        return $datasets
            ->map(fn(Dataset $dataset) => [
                'label' => $dataset->name,
                'url'   => route('projects.datasets.show.overview', [$dataset->project_id, $dataset->id]),
                'meta'  => $this->datasetMeta($dataset, $issue),
                'icon'  => 'fas fa-database',
            ])
            ->values()
            ->toArray();
    }

    private function projectDetails(Collection $projects, string $issue): array
    {
        return $projects
            ->map(fn(Project $project) => [
                'label' => $project->name,
                'url'   => route('projects.show', [$project->id]),
                'meta'  => $issue,
                'icon'  => 'fas fa-folder-open',
            ])
            ->values()
            ->toArray();
    }

    private function projectReadmeDescriptionDetails(Collection $projects): array
    {
        $readmesByProjectId = $this->projectReadmesByProjectId($projects);

        return $projects
            ->map(function (Project $project) use ($readmesByProjectId) {
                $issues = [];

                if (blank($project->description)) {
                    $issues[] = 'Missing description';
                }

                if (!$readmesByProjectId->has($project->id)) {
                    $issues[] = 'Missing README';
                }

                return [
                    'label' => $project->name,
                    'url'   => route('projects.show', [$project->id]),
                    'meta'  => implode(' / ', $issues),
                    'icon'  => 'fas fa-folder-open',
                ];
            })
            ->values()
            ->toArray();
    }

    private function projectReadmesByProjectId(Collection $projects): Collection
    {
        $projectIds = $projects->pluck('id')->filter()->values();
        $rootDirIds = $projects->pluck('rootDir.id')->filter()->values();

        if ($projectIds->isEmpty() || $rootDirIds->isEmpty()) {
            return collect();
        }

        return File::query()
                   ->whereIn('project_id', $projectIds)
                   ->whereIn('directory_id', $rootDirIds)
                   ->whereRaw('lower(name) = ?', ['readme.md'])
                   ->active()
                   ->get()
                   ->keyBy('project_id');
    }

    private function datasetHasSelectedFiles(Dataset $dataset): bool
    {
        $fileSelection = $dataset->file_selection;

        if (is_null($fileSelection)) {
            return false;
        }

        if (!empty($fileSelection['include_files'] ?? [])) {
            return true;
        }

        if (!empty($fileSelection['include_dirs'] ?? [])) {
            return true;
        }

        return false;
    }

    private function datasetMeta(Dataset $dataset, string $issue): string
    {
        $parts = [$issue];

        if ($dataset->project) {
            $parts[] = "Project: {$dataset->project->name}";
        }

        if ($dataset->published_at) {
            $parts[] = 'Published';
        } else {
            $parts[] = 'Unpublished';
        }

        return implode(' / ', $parts);
    }
}
