<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\Experiments\ShowExperimentViewModel;
use Illuminate\Support\Facades\DB;

class ShowExperimentOverviewWebController extends Controller
{
    use ExcelFilesCount;
    use DataDictionaryQueries;
    use EtlRunsCount;

    public function __invoke($projectId, $experimentId)
    {
        $experiment = Experiment::with('sheet')
                                ->withCount('experimental_entities',
                                    'computational_entities',
                                    'activities',
                                    'workflows')
                                ->with('etlruns.files')
                                ->findOrFail($experimentId);
        $showExperimentViewModel = (new ShowExperimentViewModel())
            ->withProject($project = Project::with('experiments')->findOrFail($projectId))
            ->withExperiment($experiment)
            ->withEtlRunsCount($this->getEtlRunsCount($experiment->etlruns))
            ->withExcelFilesCount($this->getExcelFilesCount($project))
            ->withActivitiesGroup($this->getActivitiesGroup($experiment->id))
            ->withFileTypes($this->getFileTypesGroup($experiment->id))
            ->withActivityAttributesCount($this->getUniqueActivityAttributesForExperiment($experiment->id)->count())
            ->withEntityAttributesCount($this->getUniqueEntityAttributesForExperiment($experiment->id)->count())
            ->withTotalFilesSize($this->getExperimentFilesTotalSize($experiment->id));
        return view('app.projects.experiments.show', $showExperimentViewModel);
    }

    private function getActivitiesGroup($experimentId)
    {
        return DB::table('activities')
                 ->select('name', DB::raw('count(*) as count'))
                 ->whereIn('id',
                     DB::table('experiment2activity')
                       ->select('activity_id')
                       ->where('experiment_id', $experimentId)
                 )
                 ->groupBy('name')
                 ->orderBy('name')
                 ->get();
    }

    private function getExperimentFilesTotalSize($experimentId)
    {
        return DB::table('files')
                 ->whereIn('id',
                     DB::Table('experiment2file')
                       ->select('file_id')
                       ->where('experiment_id', $experimentId)
                 )
                 ->where('mime_type', '<>', 'directory')
                 ->sum('size');
    }

    private function getFileTypesGroup($experimentId)
    {
        return DB::table('files')
                 ->select('mime_type', DB::raw('count(*) as count'))
                 ->whereIn('id',
                     DB::Table('experiment2file')
                       ->select('file_id')
                       ->where('experiment_id', $experimentId)
                 )
                 ->where('mime_type', '<>', 'directory')
                 ->groupBy('mime_type')
                 ->orderBy('mime_type')
                 ->get()
                 ->flatMap(function ($item) {
                     return [$item->mime_type => $item->count];
                 })
                 ->all();
    }

    private function getObjectTypes($experimentId)
    {
        $query = "select (select count(*) from entities where id in (select entity_id from experiment2entity where experiment_id = {$experimentId})) as entitiesCount,".
            "(select count(*) from activities where id in (select activity_id from experiment2activity where experiment_id = {$experimentId})) as activitiesCount,".
            "(select count(*) from files where id in (select file_id from experiment2file where experiment_id = {$experimentId}) and mime_type <> 'directory') as filesCount,".
            "(select count(*) from datasets where id in (select dataset_id from dataset2experiment where experiment_id = {$experimentId}) and published_at is null) as unpublishedDatasetsCount,".
            "(select count(*) from datasets where id in (select dataset_id from dataset2experiment where experiment_id = {$experimentId}) and published_at is not null) as publishedDatasetsCount";
        $queryString = DB::raw($query)->getValue(DB::connection()->getQueryGrammar());
        $results = DB::select($queryString);
        return $results[0];
    }
}
