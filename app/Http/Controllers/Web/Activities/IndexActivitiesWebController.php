<?php

namespace App\Http\Controllers\Web\Activities;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class IndexActivitiesWebController extends Controller
{
    public function __invoke(Project $project)
    {
        return view('app.projects.activities.index', [
            'project'          => $project,
            'activityEntities' => $this->getActivityEntityGroup($project->id),
        ]);
    }

    private function getActivityEntityGroup(int $projectId)
    {
        return DB::table('activity2entity')
                 ->leftJoin('activities', function ($join) use ($projectId) {
                     $join
                         ->on('activities.id', '=', 'activity2entity.activity_id')
                         ->where('activities.project_id', $projectId);
                 })
                 ->join('entities', 'entities.id', '=', 'activity2entity.entity_id')
                 ->select('activities.name as activityName', 'entities.name', 'entities.id')
                 ->distinct()
                 ->get()
                 ->groupBy('activityName');
    }
}
