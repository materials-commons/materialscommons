<?php

namespace App\View\Components\Projects\HealthReports;

use App\DTO\HealthReport;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Show extends Component
{
    public HealthReport $healthReport;

    public function __construct(HealthReport $healthReport)
    {
        $this->healthReport = $healthReport;
    }

    public function render(): View|Closure|string
    {
        return view('components.projects.health-reports.show');
    }

    public function getTotalIssues(): int
    {
        return $this->healthReport->missingFiles->count() +
            $this->healthReport->oldGlobusDownloads->count() +
            $this->healthReport->unpublishedDatasetsWithDOIs->count() +
            $this->healthReport->unprocessedGlobusUploads->count();
    }

    public function getHealthStatus(): string
    {
        $totalIssues = $this->getTotalIssues();

        if ($totalIssues === 0) {
            return 'healthy';
        } elseif ($totalIssues <= 5) {
            return 'warning';
        } else {
            return 'critical';
        }
    }

}
