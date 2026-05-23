<?php

namespace App\Livewire\Projects\Experiments\Etl;

use App\Enums\Etl\EtlRunValidationSeverity;
use App\Models\EtlRun;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Status extends Component
{
    public Project $project;
    public Experiment $experiment;
    public EtlRun $etlRun;
    public string $tab = 'process-results';

    protected $queryString = [
        'tab' => ['except' => 'process-results'],
    ];

    public function mount(Project $project, Experiment $experiment, EtlRun $etlRun): void
    {
        $this->project = $project;
        $this->experiment = $experiment;
        $this->etlRun = $etlRun;

        $this->refreshRun();
    }

    public function refreshRun(): void
    {
        $this->etlRun = EtlRun::query()
                              ->with([
                                  'steps',
                                  'processResults.entities',
                                  'validationMessages',
                                  'logEntries',
                                  'files',
                              ])
                              ->withCount([
                                  'validationMessages as warning_count' => fn($query) => $query->where(
                                      'severity',
                                      EtlRunValidationSeverity::Warning->value
                                  ),
                                  'validationMessages as error_count' => fn($query) => $query->where(
                                      'severity',
                                      EtlRunValidationSeverity::Error->value
                                  ),
                                  'processResults',
                                  'logEntries',
                              ])
                              ->findOrFail($this->etlRun->id);
    }

    public function setTab(string $tab): void
    {
        if (!in_array($tab, ['process-results', 'validation', 'log'], true)) {
            return;
        }

        $this->tab = $tab;
    }

    public function getShouldPollProperty(): bool
    {
        return $this->etlRun->isActive();
    }

    public function getMetricsProperty(): array
    {
        return [
            [
                'label' => 'Sheets',
                'value' => "{$this->etlRun->n_sheets_processed}/{$this->etlRun->n_sheets}",
                'color' => 'text-primary',
            ],
            [
                'label' => 'Samples',
                'value' => $this->etlRun->n_entities ?? 0,
                'color' => 'text-info',
            ],
            [
                'label' => 'Processes',
                'value' => $this->etlRun->n_activities ?? 0,
                'color' => 'text-success',
            ],
            [
                'label' => 'Warnings',
                'value' => $this->etlRun->warning_count ?? 0,
                'color' => 'text-warning',
            ],
            [
                'label' => 'Errors',
                'value' => $this->etlRun->error_count ?? 0,
                'color' => 'text-danger',
            ],
            [
                'label' => 'Results',
                'value' => $this->etlRun->process_results_count ?? 0,
                'color' => 'text-secondary',
            ],
        ];
    }

    public function render(): View
    {
        return view('livewire.projects.experiments.etl.status');
    }
}
