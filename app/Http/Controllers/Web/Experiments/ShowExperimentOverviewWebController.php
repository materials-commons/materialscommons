<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\ViewModels\Experiments\ShowExperimentViewModel;
use Illuminate\Support\Facades\DB;

class ShowExperimentOverviewWebController extends Controller
{
    use ExcelFilesCount;

    public function __invoke($projectId, Experiment $experiment)
    {
        $showExperimentViewModel = (new ShowExperimentViewModel())
            ->withProject($project = Project::with('experiments')->findOrFail($projectId))
            ->withExperiment($experiment)
            ->withExcelFilesCount($this->getExcelFilesCount($project))
            ->withActivitiesGroup($this->getActivitiesGroup($experiment->id))
            ->withFileTypes($this->getFileTypesGroup($experiment->id))
            ->withObjectCounts($this->getObjectTypes($experiment->id))
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
                 ->whereIn('id',
                     DB::Table('experiment2file')
                       ->select('file_id')
                       ->where('experiment_id', $experimentId)
                 )
                 ->where('mime_type', '<>', 'directory')
                 ->groupBy('mime_type')
                 ->orderBy('mime_type')
                 ->get();
    }

    private function getObjectTypes($experimentId)
    {
        $query = "select (select count(*) from entities where id in (select entity_id from experiment2entity where experiment_id = {$experimentId})) as entitiesCount,".
            "(select count(*) from activities where id in (select activity_id from experiment2activity where experiment_id = {$experimentId})) as activitiesCount,".
            "(select count(*) from files where id in (select file_id from experiment2file where experiment_id = {$experimentId}) and mime_type <> 'directory') as filesCount";
        $results = DB::select(DB::raw($query));
        return $results[0];
    }
}