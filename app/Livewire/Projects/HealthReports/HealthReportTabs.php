<?php

namespace App\Livewire\Projects\HealthReports;

use Livewire\Component;

class HealthReportTabs extends Component
{
    public $tab = 'missing';
    public $healthReport;

    public function render()
    {
        return view('livewire.projects.health-reports.health-report-tabs');
    }

    public function setTab($tab): void
    {
        $this->tab = $tab;
    }
}
