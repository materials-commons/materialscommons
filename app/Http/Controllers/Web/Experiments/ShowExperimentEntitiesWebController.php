<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\Traits\Entities\EntityAndAttributeQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowExperimentEntitiesWebController extends Controller
{
    use ExcelFilesCount;
    use DataDictionaryQueries;
    use EtlRunsCount;
    use EntityAndAttributeQueries;

    public function __invoke(Request $request, CreateUsedActivitiesForEntitiesAction $createUsedActivities, $projectId,
                                     $experimentId)
    {
        $category = $request->input('category');
        $experiment = Experiment::withCount('experimental_entities', 'computational_entities', 'activities',
            'workflows')
                                ->with('etlruns.files')
                                ->findOrFail($experimentId);
        $project = Project::with('experiments')->findOrFail($projectId);

        $orderByColumn = "name";
        $nullCount = DB::table('experiment2activity')
                       ->where('experiment_id', $experiment->id)
                       ->join(
                           'activities',
                           'experiment2activity.activity_id',
                           '=',
                           'activities.id'
                       )
                       ->where('activities.name', '<>', 'Create Samples')
                       ->select('activities.name', 'activities.eindex')
                       ->whereNull('eindex')
                       ->distinct()
                       ->get()
                       ->count();

        if ($nullCount == 0) {
            $orderByColumn = "eindex";
        }

        if ($category == 'experimental') {
            $entities = $experiment->experimental_entities()->with('activities')->get();
        } else {
            $entities = $experiment->computational_entities()->with('activities')->get();
        }

        $activities = $this->getExperimentActivityNamesEindexForEntities($experiment->id, $entities, $orderByColumn);

        return view('app.projects.experiments.show', [
            'project'                 => $project,
            'experiment'              => $experiment,
            'excelFilesCount'         => $this->getExcelFilesCount($project),
            'activities'              => $activities,
            'entities'                => $entities,
            'etlRunsCount'            => $this->getEtlRunsCount($experiment->etlruns),
            'activityAttributesCount' => $this->getUniqueActivityAttributesForExperiment($experiment->id)->count(),
            'entityAttributesCount'   => $this->getUniqueEntityAttributesForExperiment($experiment->id)->count(),
            'usedActivities'          => $createUsedActivities->execute($activities, $entities),
        ]);
    }
}
