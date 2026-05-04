<?php

namespace App\View\Components\Dashboard\MyResearch;

use App\Models\Dataset;
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
        $query = $this->datasetsUserIsPartOf()
                      ->where(function (Builder $query) {
                          $query->whereNull('license')
                                ->orWhere('license', '');
                      });

        $count = (clone $query)->count();

        if ($count === 0) {
            return null;
        }

        $datasets = (clone $query)
            ->with('project')
            ->orderBy('name')
            ->limit($this->detailsLimit)
            ->get();

        return [
            'key'            => 'missing-licenses',
            'title'          => 'Datasets missing licenses',
            'description'    => 'Add license information so datasets are ready for publication and reuse.',
            'count'          => $count,
            'icon'           => 'fas fa-balance-scale',
            'badgeClass'     => 'text-bg-danger',
            'priority'       => 100,
            'actionLabel'    => 'Review licenses',
            'actionUrl'      => null,
            'detailsLabel'   => 'Datasets missing licenses',
            'details'        => $this->datasetDetails($datasets, 'Missing license'),
            'remainingCount' => max($count - $datasets->count(), 0),
        ];
    }

    private function missingAuthorsItem(): ?array
    {
        $query = $this->datasetsUserIsPartOf()
                      ->where(function (Builder $query) {
                          $query->whereNull('ds_authors')
                                ->orWhereJsonLength('ds_authors', 0);
                      });

        $count = (clone $query)->count();

        if ($count === 0) {
            return null;
        }

        $datasets = (clone $query)
            ->with('project')
            ->orderBy('name')
            ->limit($this->detailsLimit)
            ->get();

        return [
            'key'            => 'missing-authors',
            'title'          => 'Datasets missing authors',
            'description'    => 'Add dataset authors so attribution is complete before publishing or sharing.',
            'count'          => $count,
            'icon'           => 'fas fa-user-edit',
            'badgeClass'     => 'text-bg-danger',
            'priority'       => 98,
            'actionLabel'    => 'Review authors',
            'actionUrl'      => null,
            'detailsLabel'   => 'Datasets missing authors',
            'details'        => $this->datasetDetails($datasets, 'Missing authors'),
            'remainingCount' => max($count - $datasets->count(), 0),
        ];
    }

    private function missingDescriptionItem(): ?array
    {
        $query = $this->datasetsUserIsPartOf()
                      ->where(function (Builder $query) {
                          $query->whereNull('description')
                                ->orWhere('description', '')
                                ->orWhereNull('summary')
                                ->orWhere('summary', '');
                      });

        $count = (clone $query)->count();

        if ($count === 0) {
            return null;
        }

        $datasets = (clone $query)
            ->with('project')
            ->orderBy('name')
            ->limit($this->detailsLimit)
            ->get();

        return [
            'key'            => 'missing-descriptions',
            'title'          => 'Datasets missing descriptions',
            'description'    => 'Complete summaries and descriptions to improve discoverability and publication readiness.',
            'count'          => $count,
            'icon'           => 'fas fa-align-left',
            'badgeClass'     => 'text-bg-warning',
            'priority'       => 90,
            'actionLabel'    => 'Review metadata',
            'actionUrl'      => null,
            'detailsLabel'   => 'Datasets missing descriptions',
            'details'        => $this->datasetDetails($datasets, 'Missing summary or description'),
            'remainingCount' => max($count - $datasets->count(), 0),
        ];
    }

    private function publishedDatasetsMissingTagsItem(): ?array
    {
        $query = $this->datasetsUserIsPartOf()
                      ->whereNotNull('published_at')
                      ->doesntHave('tags');

        $count = (clone $query)->count();

        if ($count === 0) {
            return null;
        }

        $datasets = (clone $query)
            ->with('project')
            ->orderBy('name')
            ->limit($this->detailsLimit)
            ->get();

        return [
            'key'            => 'published-missing-tags',
            'title'          => 'Published datasets missing tags',
            'description'    => 'Add tags to published datasets to improve search, browsing, and discoverability.',
            'count'          => $count,
            'icon'           => 'fas fa-tags',
            'badgeClass'     => 'text-bg-success',
            'priority'       => 88,
            'actionLabel'    => 'Review tags',
            'actionUrl'      => null,
            'detailsLabel'   => 'Published datasets missing tags',
            'details'        => $this->datasetDetails($datasets, 'Published without tags'),
            'remainingCount' => max($count - $datasets->count(), 0),
        ];
    }

    private function draftsWithoutFilesItem(): ?array
    {
        $datasets = $this->datasetsUserIsPartOf()
                         ->whereNull('published_at')
                         ->with('project')
                         ->orderBy('name')
                         ->get()
                         ->filter(fn(Dataset $dataset) => !$dataset->hasSelectedFiles())
                         ->values();

        $count = $datasets->count();

        if ($count === 0) {
            return null;
        }

        $visibleDatasets = $datasets->take($this->detailsLimit);

        return [
            'key'            => 'drafts-without-files',
            'title'          => 'Draft datasets without selected files',
            'description'    => 'Select files for draft datasets before publishing or sharing them.',
            'count'          => $count,
            'icon'           => 'fas fa-file-circle-question',
            'badgeClass'     => 'text-bg-info',
            'priority'       => 80,
            'actionLabel'    => 'Review drafts',
            'actionUrl'      => null,
            'detailsLabel'   => 'Draft datasets without selected files',
            'details'        => $this->datasetDetails($visibleDatasets, 'No selected files'),
            'remainingCount' => max($count - $visibleDatasets->count(), 0),
        ];
    }

    private function unpublishedDatasetsOlderThanThreeMonthsItem(): ?array
    {
        $query = $this->datasetsUserIsPartOf()
                      ->whereNull('published_at')
                      ->where('updated_at', '<', now()->subMonths(3));

        $count = (clone $query)->count();

        if ($count === 0) {
            return null;
        }

        $datasets = (clone $query)
            ->with('project')
            ->orderBy('updated_at')
            ->limit($this->detailsLimit)
            ->get();

        return [
            'key'            => 'old-unpublished-datasets',
            'title'          => 'Unpublished datasets older than 3 months',
            'description'    => 'Review older unpublished datasets and decide whether they should be updated, published, or removed.',
            'count'          => $count,
            'icon'           => 'fas fa-clock',
            'badgeClass'     => 'text-bg-secondary',
            'priority'       => 75,
            'actionLabel'    => 'Review unpublished datasets',
            'actionUrl'      => null,
            'detailsLabel'   => 'Older unpublished datasets',
            'details'        => $this->datasetDetails($datasets, 'Last updated over 3 months ago'),
            'remainingCount' => max($count - $datasets->count(), 0),
        ];
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
            ->with('files')
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

    private function datasetsUserIsPartOf()
    {
        return Dataset::whereIn('id', $this->datasetIdsUserIsPartOf());
    }

    private function datasetIdsUserIsPartOf(): Collection
    {
        $ownedDatasetIds = Dataset::where('owner_id', $this->user->id)
                                  ->pluck('id');

        $linkedDatasetIds = $this->user
            ->datasets()
            ->pluck('datasets.id');

        return $ownedDatasetIds
            ->merge($linkedDatasetIds)
            ->unique()
            ->values();
    }

    private function projectIds(): Collection
    {
        return $this->user
            ->projects()
            ->pluck('projects.id')
            ->unique()
            ->values();
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
        return $projects
            ->map(function (Project $project) {
                $issues = [];

                if (blank($project->description)) {
                    $issues[] = 'Missing description';
                }

                $hasReadme = $project->files
                    ->contains(fn($file) => strtolower($file->name) === 'readme.md'
                        && $file->current
                        && is_null($file->dataset_id)
                        && is_null($file->deleted_at));

                if (!$hasReadme) {
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
