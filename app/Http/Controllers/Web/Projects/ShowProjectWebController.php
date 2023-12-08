<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\Projects\ShowProjectViewModel;
use Illuminate\Support\Facades\DB;

class ShowProjectWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke($projectId)
    {
        $project = Project::with(['owner', 'team.members', 'team.admins', 'rootDir'])
                          ->withCount('experiments', 'entities', 'publishedDatasets', 'unpublishedDatasets')
                          ->where('id', $projectId)
                          ->first();
        $readme = File::where('name', "readme.md")
                      ->where("project_id", $projectId)
                      ->where("directory_id", $project->rootDir->id)
                      ->where('current', true)
                      ->whereNull('dataset_id')
                      ->whereNull('deleted_at')
                      ->first();
        $showProjectViewModel = (new ShowProjectViewModel($project))
            ->withActivityAttributesCount($this->getUniqueActivityAttributesForProject($projectId)->count())
            ->withEntityAttributesCount($this->getUniqueEntityAttributesForProject($projectId)->count())
            ->withActivitiesGroup($this->getActivitiesGroup($project->id))
            ->withFileDescriptionTypes($project->file_types)
            ->withReadme($readme)
            ->withObjectCounts($this->getObjectTypes($project->id));
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
            "(select count(*) from experiments where project_id = {$projectId}) as experimentsCount,".
            "(select count(*) from datasets where project_id = {$projectId} and published_at is null) as unpublishedDatasetsCount,".
            "(select count(*) from datasets where project_id = {$projectId} and published_at is not null) as publishedDatasetsCount";
        $results = DB::select(DB::raw($query));
        return $results[0];
    }
}
