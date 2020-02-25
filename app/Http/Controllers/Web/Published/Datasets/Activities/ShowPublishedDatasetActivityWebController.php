<?php

namespace App\Http\Controllers\Web\Published\Datasets\Activities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Dataset;

class ShowPublishedDatasetActivityWebController extends Controller
{
    public function __invoke(Dataset $dataset, $activityId)
    {
        $activity = Activity::with('attributes.values')->findOrFail($activityId);
        return view('public.datasets.activities.show', compact('dataset', 'activity'));
    }
}
