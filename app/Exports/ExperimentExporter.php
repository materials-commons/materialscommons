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
        $this->getExperimentActivities()
             ->each(function ($activityName) use (&$sheets) {

             });
        return $sheets;
    }

    private function getExperimentActivities()
    {
        $this->experiment->load('activities');
        return $this->experiment->activities->map(function ($activity) {
            return $activity->name;
        })->unique();
    }
}