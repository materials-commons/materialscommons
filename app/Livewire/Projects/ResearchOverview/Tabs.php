<?php

namespace App\Livewire\Projects\ResearchOverview;

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
        ray("Tabs");
        $this->project = $project;
        $this->readme = $readme;

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
            default => [],
        };
    }

    public function render(): View
    {
        return view('livewire.projects.research-overview.tabs');
    }
}
