<?php

namespace App\Actions\Projects;

use App\Models\Activity;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Traits\Projects\UserProjects;
use Illuminate\Support\Collection;

class SearchAcrossProjectsAction
{
    use UserProjects;

    public function __invoke($search, User $user)
    {
        // Get an array of project ids that the user has access to. Use this to limit what
        // objects are returned in the search.
        $projectIds = $this->getUserProjects($user->id)->map(function (Project $project) {
            return $project->id;
        })->toArray();

        // Search each model type with Laravel Scout
        $projectResults = Project::search($search)
                                 ->whereIn('id', $projectIds)
                                 ->take(10)
                                 ->get();

        $fileResults = File::search($search)
                           ->query(function ($query) {
                               return $query->with(['directory', 'project']);
                           })
                           ->whereIn('project_id', $projectIds)
                           ->where('dataset_id', null)
                           ->where('deleted_at', null)
                           ->where('current', true)
                           ->take(20)
                           ->get();

        $experimentResults = Experiment::search($search)
                                       ->query(function ($query) {
                                           return $query->with(['project']);
                                       })
                                       ->whereIn('project_id', $projectIds)
                                       ->take(10)
                                       ->get();

        $entityResults = Entity::search($search)
                               ->query(function ($query) {
                                   return $query->with(['project', 'experiments']);
                               })
                               ->whereIn('project_id', $projectIds)
                               ->take(10)
                               ->get();

        $activityResults = Activity::search($search)
                                   ->query(function ($query) {
                                       return $query->with(['project', 'experiments']);
                                   })
                                   ->whereIn('project_id', $projectIds)
                                   ->take(10)
                                   ->get();

        $datasetResults = Dataset::search($search)
                                 ->query(function ($query) {
                                     return $query->with(['project']);
                                 })
                                 ->whereIn('project_id', $projectIds)
                                 ->take(10)
                                 ->get();

        $communityResults = Community::search($search)
                                     ->query(function ($query) {
                                         return $query->with(['project']);
                                     })
                                     ->where('public', true)
                                     ->take(10)
                                     ->get();

        $searchResults = new Collection([
            $projectResults, $fileResults, $experimentResults, $entityResults, $activityResults, $datasetResults,
            $communityResults
        ]);

        return $searchResults->collapse();
    }
}
