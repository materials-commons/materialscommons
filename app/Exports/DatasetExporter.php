<?php

namespace App\Exports;

use App\Models\Activity;
use App\Models\Dataset;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use LaravelIdea\Helper\App\Models\_IH_Activity_C;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DatasetExporter implements WithMultipleSheets
{
    private Dataset $dataset;

    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    public function sheets(): array
    {
        $sheets = [];
        $this->getDatasetActivities()
             ->each(function ($activityName) use (&$sheets) {
                 $sheets[] = new DatasetActivityExport($activityName, $this->dataset);
             });
        return $sheets;
    }

    private function getDatasetActivities(): \Illuminate\Support\Collection
    {
        $orderBy = $this->columnToOrderActivitiesBy();
        return DB::table('activities')->select(['name', 'eindex'])
                 ->where('dataset_id', $this->dataset->id)
                 ->orderBy($orderBy)
                 ->distinct()
                 ->get()
                 ->pluck('name');
//        return Activity::query() //::with(["attributes.values", "entityStates.attributes.values", "entityStates.entity"])
//                       ->where('dataset_id', $this->dataset->id)
//                       ->orderBy($orderBy)
//                       ->get();
//                       ->groupBy('name');
    }

    // Determine if we can use eindex to order activities, or if its null
    // use name.
    private function columnToOrderActivitiesBy(): string
    {
        $nullCount = Activity::where('dataset_id', $this->dataset->id)
                             ->whereNull('eindex')
                             ->count();

        if ($nullCount == 0) {
            return "eindex";
        }

        return "name";
    }
}