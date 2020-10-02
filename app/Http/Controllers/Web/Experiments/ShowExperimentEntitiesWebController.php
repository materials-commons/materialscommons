<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use Illuminate\Support\Facades\DB;

class ShowExperimentEntitiesWebController extends Controller
{
    use ExcelFilesCount;
    use DataDictionaryQueries;

    public function __invoke(CreateUsedActivitiesForEntitiesAction $createUsedActivities, $projectId, $experimentId)
    {
        $experiment = Experiment::withCount('entities', 'activities', 'workflows')->findOrFail($experimentId);
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
            'project'                 => $project,
            'experiment'              => $experiment,
            'excelFilesCount'         => $this->getExcelFilesCount($project),
            'activities'              => $activities,
            'entities'                => $entities,
            'activityAttributesCount' => $this->getUniqueActivityAttributesForExperiment($experiment->id)->count(),
            'entityAttributesCount'   => $this->getUniqueEntityAttributesForExperiment($experiment->id)->count(),
            'usedActivities'          => $createUsedActivities->execute($activities, $entities),
        ]);
    }
}
