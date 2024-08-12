<?php

namespace App\Exports;

use App\Models\Activity;
use App\Models\Experiment;
use App\ViewModels\Export\ActivityExportViewModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use function view;

class ExperimentActivityExport implements FromView, WithTitle
{
    private string $activityName;

    private Experiment $experiment;

    public function __construct(string $activityName, Experiment $experiment)
    {
        $this->activityName = $activityName;
        $this->experiment = $experiment;
    }

    public function title(): string
    {
        return $this->activityName;
    }

    public function view(): View
    {
        $activities = $this->getActivities();
        $viewModel = new ActivityExportViewModel($activities, $this->activityName);

        return view('exports.activity', $viewModel);
    }

    private function getActivities()
    {
        return Activity::with(["entityStates.entity", "entityStates.attributes.values", "attributes.values"])
                       ->whereHas("experiments", function ($query) {
                           $query->where("experiment_id", $this->experiment->id);
                       })
                       ->where("name", $this->activityName)
                       ->get();
    }
}