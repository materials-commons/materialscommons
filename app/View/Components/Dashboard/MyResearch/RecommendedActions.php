<?php

namespace App\View\Components\Dashboard\MyResearch;

use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class RecommendedActions extends Component
{
    public array $actions;

    public bool $hasActions;

    private User $user;

    private int $limit = 5;

    public function __construct()
    {
        $this->user = auth()->user();
        $this->actions = $this->buildActions();
        $this->hasActions = count($this->actions) > 0;
    }

    public function render(): View
    {
        return view('components.dashboard.my-research.recommended-actions');
    }

    private function buildActions(): array
    {
        return collect([
            $this->researchSummaryAction(),
            $this->orcidAction(),
            $this->affiliationAction(),
            $this->publishReadyCandidatesAction(),
            $this->oldUnpublishedDatasetsAction(),
            $this->projectDocumentationAction(),
            $this->publishedDatasetsMissingTagsAction(),
        ])
            ->filter()
            ->sortByDesc('priority')
            ->values()
            ->take($this->limit)
            ->toArray();
    }

    private function researchSummaryAction(): ?array
    {
        if (!blank($this->user->research_summary)) {
            return null;
        }

        return [
            'title'       => 'Create your research summary',
            'description' => 'Add a private summary of your research interests, active work, datasets, and publication goals.',
            'icon'        => 'fas fa-compass',
            'badge'       => 'Profile',
            'badgeClass'  => 'text-bg-primary',
            'priority'    => 100,
            'actionLabel' => 'Create summary',
            'actionUrl'   => null,
            'modalTarget' => '#research-summary-modal',
        ];
    }

    private function orcidAction(): ?array
    {
        if (!blank($this->user->orcid)) {
            return null;
        }

        return [
            'title'       => 'Add your ORCID',
            'description' => 'Adding your ORCID improves attribution and helps connect your datasets with your scholarly identity.',
            'icon'        => 'fas fa-id-badge',
            'badge'       => 'Profile',
            'badgeClass'  => 'text-bg-secondary',
            'priority'    => 90,
            'actionLabel' => 'Update profile',
            'actionUrl'   => null,
            'modalTarget' => null,
        ];
    }

    private function affiliationAction(): ?array
    {
        if (!blank($this->user->affiliations)) {
            return null;
        }

        return [
            'title'       => 'Add your affiliation',
            'description' => 'Affiliations help collaborators and dataset viewers understand your institutional context.',
            'icon'        => 'fas fa-building',
            'badge'       => 'Profile',
            'badgeClass'  => 'text-bg-secondary',
            'priority'    => 85,
            'actionLabel' => 'Update profile',
            'actionUrl'   => null,
            'modalTarget' => null,
        ];
    }

    private function publishReadyCandidatesAction(): ?array
    {
        $count = $this->datasetsUserIsPartOf()
                      ->whereNull('published_at')
                      ->whereNotNull('license')
                      ->where('license', '<>', '')
                      ->whereNotNull('description')
                      ->where('description', '<>', '')
                      ->whereNotNull('summary')
                      ->where('summary', '<>', '')
                      ->whereNotNull('ds_authors')
                      ->whereJsonLength('ds_authors', '>', 0)
                      ->get()
                      ->filter(fn(Dataset $dataset) => $dataset->hasSelectedFiles())
                      ->count();

        if ($count === 0) {
            return null;
        }

        return [
            'title'       => 'Review publish-ready dataset candidates',
            'description' => "{$count} unpublished dataset" . ($count === 1 ? ' appears' : 's appear') . " to have authors, license, description, summary, and selected files.",
            'icon'        => 'fas fa-upload',
            'badge'       => number_format($count),
            'badgeClass'  => 'text-bg-success',
            'priority'    => 80,
            'actionLabel' => 'Review candidates',
            'actionUrl'   => null,
            'modalTarget' => null,
        ];
    }

    private function oldUnpublishedDatasetsAction(): ?array
    {
        $count = $this->datasetsUserIsPartOf()
                      ->whereNull('published_at')
                      ->where('updated_at', '<', now()->subMonths(3))
                      ->count();

        if ($count === 0) {
            return null;
        }

        return [
            'title'       => 'Review older unpublished datasets',
            'description' => "{$count} unpublished dataset" . ($count === 1 ? ' has' : 's have') . " not been updated in over 3 months.",
            'icon'        => 'fas fa-clock',
            'badge'       => number_format($count),
            'badgeClass'  => 'text-bg-warning',
            'priority'    => 70,
            'actionLabel' => 'Review datasets',
            'actionUrl'   => null,
            'modalTarget' => null,
        ];
    }

    private function projectDocumentationAction(): ?array
    {
        $count = Project::whereIn('id', $this->projectIds())
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
                        })
                        ->count();

        if ($count === 0) {
            return null;
        }

        return [
            'title'       => 'Improve project documentation',
            'description' => "{$count} active project" . ($count === 1 ? ' is' : 's are') . " missing a README or description.",
            'icon'        => 'fas fa-book-open',
            'badge'       => number_format($count),
            'badgeClass'  => 'text-bg-info',
            'priority'    => 60,
            'actionLabel' => 'View projects',
            'actionUrl'   => route('dashboard.projects.show'),
            'modalTarget' => null,
        ];
    }

    private function publishedDatasetsMissingTagsAction(): ?array
    {
        $count = $this->datasetsUserIsPartOf()
                      ->whereNotNull('published_at')
                      ->doesntHave('tags')
                      ->count();

        if ($count === 0) {
            return null;
        }

        return [
            'title'       => 'Improve dataset discoverability',
            'description' => "{$count} published dataset" . ($count === 1 ? ' is' : 's are') . " missing tags.",
            'icon'        => 'fas fa-tags',
            'badge'       => number_format($count),
            'badgeClass'  => 'text-bg-success',
            'priority'    => 50,
            'actionLabel' => 'Review tags',
            'actionUrl'   => null,
            'modalTarget' => null,
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
}
