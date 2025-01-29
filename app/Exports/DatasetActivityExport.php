<?php

namespace App\Exports;

use App\Models\Activity;
use App\Models\Dataset;
use App\Models\Entity;
use App\ViewModels\Export\ActivityExportViewModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use function collect;
use function view;

class DatasetActivityExport implements FromView, WithTitle
{
    private string $activityName;
    private Dataset $dataset;

    public function __construct($activityName, $dataset)
    {
        $this->activityName = $activityName;
        $this->dataset = $dataset;
    }

    public function title(): string
    {
        return $this->activityName;
    }

    public function view(): View
    {
        $activities = $this->getActivities();
        $samples = $this->getEntities($activities);

        $viewModel = new ActivityExportViewModel($activities, $samples);

        return view('exports.activity', $viewModel);
    }

    private function getActivities()
    {
        return Activity::with("entities", "attributes.values")
                       ->where("dataset_id", $this->dataset->id)
                       ->where("name", $this->activityName)
                       ->get();
    }

    private function getEntities($activities)
    {
        // Get the activities with their entities, and then gather the entities into a single collection.
        $entities = collect();
        $activities->each(function (Activity $activity) use ($entities) {
            $activity->entities->each(function (Entity $entity) use ($entities, $activity) {
                $entities->push([$entity, $activity]);
            });
        });
        return $entities;
    }
}