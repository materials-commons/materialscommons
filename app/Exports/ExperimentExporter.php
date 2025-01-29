<?php

namespace App\Exports;

use App\Models\Experiment;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExperimentExporter implements WithMultipleSheets
{

    private Experiment $experiment;

    public function __construct(Experiment $experiment)
    {
        $this->experiment = $experiment;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->getExperimentActivities() as $activityName) {
            $sheets[] = new ExperimentActivityExport($activityName, $this->experiment);
        }
        return $sheets;
    }

    private function getExperimentActivities()
    {
        return $this->experiment->activities->map(function ($activity) {
            return $activity->name;
        })->unique();
    }
}