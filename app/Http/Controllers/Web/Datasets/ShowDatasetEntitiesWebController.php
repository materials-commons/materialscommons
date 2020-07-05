<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ShowDatasetEntitiesWebController extends Controller
{
    public function __invoke(CreateUsedActivitiesForEntitiesAction $createUsedActivities, Project $project, $datasetId)
    {
        $dataset = Dataset::with(['experiments', 'entities.activities', 'tags'])->find($datasetId);
        $activities = DB::table('dataset2entity')
                        ->where('dataset_id', $datasetId)
                        ->join('activity2entity', 'dataset2entity.entity_id', '=', 'activity2entity.entity_id')
                        ->join('activities', 'activity2entity.activity_id', '=', 'activities.id')
                        ->where('name', '<>', 'Create Samples')
                        ->select('name')
                        ->distinct()
                        ->orderBy('name')
                        ->get();
        return view('app.projects.datasets.show', [
            'project'        => $project,
            'dataset'        => $dataset,
            'entities'       => $dataset->entities,
            'activities'     => $activities,
            'usedActivities' => $createUsedActivities->execute($activities, $dataset->entities),
        ]);
    }
}
