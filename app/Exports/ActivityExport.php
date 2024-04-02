<?php

namespace App\Exports;

use App\Models\Activity;
use App\Models\Attribute;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\EntityState;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use function collect;
use function view;

class ActivityExport implements FromView, WithTitle
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
        $uniqueAttributeNames = $this->getUniqueActivityAttributeNames($activities);
        $samples = $this->getEntities($activities);
        $longestSampleNameLen = 0;
        $samples->each(function ($entityActivity) use (&$longestSampleNameLen) {
            $entity = $entityActivity[0];
            $len = strlen($entity->name);
            if ($len > $longestSampleNameLen) {
                $longestSampleNameLen = $len;
            }
        });
        return view('exports.activity', [
            'samples'              => $samples,
            'longestSampleNameLen' => $longestSampleNameLen,
            'uniqueAttributeNames' => $uniqueAttributeNames,
        ]);
    }

    private function getActivities()
    {
        return Activity::with("entities", "attributes.values")
                       ->where("dataset_id", $this->dataset->id)
                       ->where("name", $this->activityName)
                       ->get();
    }

    private function getUniqueActivityAttributeNames($activities)
    {
        $uniqueAttributeNames = collect();
        $activities->each(function (Activity $activity) use ($uniqueAttributeNames) {
            $activity->attributes->each(function (Attribute $attribute) use ($uniqueAttributeNames) {
                $unit = "";
                if ($attribute->values->count() !== 0) {
                    if (!blank($attribute->values[0]->unit)) {
                        $unit = "({$attribute->values[0]->unit})";
                    }
                }
                $uniqueAttributeNames->put($attribute->name, $unit);
            });
        });

        return $uniqueAttributeNames;
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