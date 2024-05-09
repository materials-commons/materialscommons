<?php

namespace App\ViewModels\Export;

//use Illuminate\Database\Eloquent\Collection;
use App\Models\Activity;
use App\Models\Attribute;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;
use function blank;
use function collect;

class ActivityExportViewModel extends ViewModel
{
    private Collection $activities;

    private Collection $samples;

    private Collection $uniqueActivityAttributeNames;

    public function __construct($activities, $samples)
    {
        $this->activities = $activities;
        $this->samples = $samples;
        $this->uniqueActivityAttributeNames = collect();
    }

    public function activities(): Collection
    {
        return $this->activities;
    }

    public function samples(): Collection
    {
        return $this->samples;
    }

    public function uniqueActivityAttributeNames(): Collection
    {
        if ($this->uniqueActivityAttributeNames->count() != 0) {
            return $this->uniqueActivityAttributeNames;
        }

        $this->uniqueActivityAttributeNames = collect();
        $this->activities->each(function (Activity $activity) {
            $activity->attributes->each(function (Attribute $attribute) {
                $unit = "";
                if ($attribute->values->count() !== 0) {
                    if (!blank($attribute->values[0]->unit)) {
                        $unit = "({$attribute->values[0]->unit})";
                    }
                }
                $this->uniqueActivityAttributeNames->put($attribute->name, $unit);
            });
        });

        return $this->uniqueActivityAttributeNames;
    }

    public function uniqueEntityAttributeNames(): Collection
    {

    }

    public function longestSampleNameLen(): int
    {
        $longest = $this->samples->max(function ($sample) {
            return strlen($sample[0]->name);
        });
        return $longest;
    }
}
