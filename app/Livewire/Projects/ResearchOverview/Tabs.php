<?php

namespace App\Livewire\Projects\ResearchOverview;

use App\Actions\Projects\ResearchOverview\BuildDatasetsTabMetricsAction;
use App\Actions\Projects\ResearchOverview\BuildFilesTabMetricsAction;
use App\Actions\Projects\ResearchOverview\BuildStudiesTabMetricsAction;
use App\Models\File;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class Tabs extends Component
{
    public Project $project;

    public ?File $readme = null;

    #[Url(as: 'researchTab')]
    public string $tab = 'overview';

    /**
     * @var array<string, mixed>
     */
    public array $metrics = [];

    /**
     * @var array<string, int>
     */
    public array $tabCounts = [
        'files' => 0,
        'studies' => 0,
        'datasets' => 0,
        'samples' => 0,
        'processes' => 0,
        'metadata' => 0,
        'collaborators' => 0,
    ];

    /**
     * @var array<int, string>
     */
    private array $allowedTabs = [
        'overview',
        'files',
        'studies',
        'datasets',
        'samples',
        'processes',
        'metadata',
        'collaborators',
        'health',
        'activity',
    ];

    public function mount(Project $project, ?File $readme = null): void
    {
        $this->project = $project;
        $this->readme = $readme;
        $this->tabCounts = $this->buildTabCounts($project);

        if (!in_array($this->tab, $this->allowedTabs, true)) {
            $this->tab = 'overview';
        }

        $this->loadTabMetrics();
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
        $this->loadTabMetrics();
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
        $this->loadTabMetrics();

        $this->dispatch('project-research-overview-tab-changed', tab: $tab);
    }

    private function loadTabMetrics(): void
    {
        $this->metrics = match ($this->tab) {
            'files' => app(BuildFilesTabMetricsAction::class)->execute($this->project),
            'studies' => app(BuildStudiesTabMetricsAction::class)->execute($this->project),
            'datasets' => app(BuildDatasetsTabMetricsAction::class)->execute($this->project),
            default => [],
        };
    }

    private function buildTabCounts(Project $project): array
    {
        $project->loadMissing(['team.members', 'team.admins']);

        $counts = Project::query()
                         ->where('id', $project->id)
                         ->withCount(['experiments', 'entities', 'publishedDatasets', 'unpublishedDatasets'])
                         ->first();

        $publishedDatasetsCount = (int) ($counts?->published_datasets_count ?? 0);
        $unpublishedDatasetsCount = (int) ($counts?->unpublished_datasets_count ?? 0);
        $entityAttributesCount = (int) ($project->entityAttributesCount ?? 0);
        $activityAttributesCount = (int) ($project->activityAttributesCount ?? 0);

        return [
            'files' => (int) ($project->file_count ?? 0),
            'studies' => (int) ($counts?->experiments_count ?? 0),
            'datasets' => $publishedDatasetsCount + $unpublishedDatasetsCount,
            'samples' => (int) ($counts?->entities_count ?? 0),
            'processes' => $activityAttributesCount,
            'metadata' => $entityAttributesCount + $activityAttributesCount,
            'collaborators' => collect($project->team?->members ?? collect())->count(),
        ];
    }

    public function render(): View
    {
        return view('livewire.projects.research-overview.tabs');
    }
}
