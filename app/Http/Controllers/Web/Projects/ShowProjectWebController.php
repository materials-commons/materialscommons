<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\ViewModels\Projects\ShowProjectViewModel;
use Illuminate\Support\Facades\DB;

class ShowProjectWebController extends Controller
{
    public function __invoke($projectId)
    {
        $project = Project::with(['owner', 'team.members', 'team.admins'])
                          ->withCount('experiments', 'entities', 'publishedDatasets', 'unpublishedDatasets',
                              'onlyFiles')
                          ->where('id', $projectId)
                          ->first();
        $showProjectViewModel = (new ShowProjectViewModel($project))
            ->withActivitiesGroup($this->getActivitiesGroup($project->id))
            ->withFileTypes($this->getFileTypesGroup($project->id))
            ->withObjectCounts($this->getObjectTypes($project->id))
            ->withTotalFilesSize($this->getProjectSize($project->id));
        return view('app.projects.show', $showProjectViewModel);
    }

    private function getActivitiesGroup($projectId)
    {
        return DB::table('activities')
                 ->select('name', DB::raw('count(*) as count'))
                 ->where('project_id', $projectId)
                 ->groupBy('name')
                 ->orderBy('name')
                 ->get();
    }

    private function getProjectSize($projectId)
    {
        return DB::table('files')
                 ->where('project_id', $projectId)
                 ->sum('size');
    }

    private function getFileTypesGroup($projectId)
    {
        return DB::table('files')
                 ->select('mime_type', DB::raw('count(*) as count'))
                 ->where('project_id', $projectId)
                 ->where('mime_type', '<>', 'directory')
                 ->groupBy('mime_type')
                 ->orderBy('mime_type')
                 ->get();
    }

    private function getObjectTypes($projectId)
    {
        $query = "select (select count(*) from entities where project_id = {$projectId}) as entitiesCount,".
            "(select count(*) from activities where project_id = {$projectId}) as activitiesCount,".
            "(select count(*) from files where project_id = {$projectId} and mime_type <> 'directory') as filesCount,".
            "(select count(*) from experiments where project_id = {$projectId}) as experimentsCount,".
            "(select count(*) from datasets where project_id = {$projectId} and published_at is null) as unpublishedDatasetsCount,".
            "(select count(*) from datasets where project_id = {$projectId} and published_at is not null) as publishedDatasetsCount";
        $results = DB::select(DB::raw($query));
        return $results[0];
    }
}
