<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ShowExperimentEntitiesWebController extends Controller
{
    use ExcelFilesCount;

    public function __invoke(CreateUsedActivitiesForEntitiesAction $createUsedActivities, $projectId,
        Experiment $experiment)
    {
        $project = Project::with('experiments')->findOrFail($projectId);
        $activities = DB::table('experiment2activity')
                        ->where('experiment_id', $experiment->id)
                        ->join('activities', 'experiment2activity.activity_id', '=', 'activities.id')
                        ->where('activities.name', '<>', 'Create Samples')
                        ->select('activities.name')
                        ->distinct()
                        ->orderBy('name')
                        ->get();

        $entities = $experiment->entities()->with('activities')->get();

        return view('app.projects.experiments.show', [
            'project'         => $project,
            'experiment'      => $experiment,
            'excelFilesCount' => $this->getExcelFilesCount($project),
            'activities'      => $activities,
            'entities'        => $entities,
            'usedActivities'  => $createUsedActivities->execute($activities, $entities),
        ]);
    }
}
