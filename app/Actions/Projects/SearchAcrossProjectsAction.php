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
use App\Models\Workflow;
use Illuminate\Support\Collection;
use Spatie\Searchable\SearchResult;
use Spatie\Searchable\SearchResultCollection;

class SearchAcrossProjectsAction
{
    public function __invoke($search, User $user)
    {
        $projectIds = $user->projects->map(function (Project $project) {
            return $project->id;
        })->toArray();

        // Search each model type with Laravel Scout
        $projectResults = Project::search($search)
            ->whereIn('id', $projectIds)
            ->take(10)
            ->get();

        $fileResults = File::search($search)
            ->whereIn('project_id', $projectIds)
            ->take(20)
            ->get();

        $experimentResults = Experiment::search($search)
            ->whereIn('project_id', $projectIds)
            ->take(10)
            ->get();

        $entityResults = Entity::search($search)
            ->whereIn('project_id', $projectIds)
            ->take(10)
            ->get();

        $activityResults = Activity::search($search)
            ->whereIn('project_id', $projectIds)
            ->take(10)
            ->get();

        $workflowResults = Workflow::search($search)
            ->whereIn('project_id', $projectIds)
            ->take(10)
            ->get();

        $datasetResults = Dataset::search($search)
            ->whereIn('project_id', $projectIds)
            ->take(10)
            ->get();

        $communityResults = Community::search($search)
            ->where('public', true)
            ->take(10)
            ->get();

        // Convert to SearchResult objects for compatibility with the view
        $searchResults = new Collection();

        foreach ($projectResults as $project) {
            $searchResults->push(new SearchResult($project, $project->name, $project->getScoutUrl()));
        }

        foreach ($fileResults as $file) {
            $searchResults->push(new SearchResult($file, $file->name, $file->getScoutUrl()));
        }

        foreach ($experimentResults as $experiment) {
            $searchResults->push(new SearchResult($experiment, $experiment->name, $experiment->getScoutUrl()));
        }

        foreach ($entityResults as $entity) {
            $searchResults->push(new SearchResult($entity, $entity->name, $entity->getScoutUrl()));
        }

        foreach ($activityResults as $activity) {
            $searchResults->push(new SearchResult($activity, $activity->name, $activity->getScoutUrl()));
        }

        foreach ($workflowResults as $workflow) {
            $searchResults->push(new SearchResult($workflow, $workflow->name, $workflow->getScoutUrl()));
        }

        foreach ($datasetResults as $dataset) {
            $searchResults->push(new SearchResult($dataset, $dataset->name, $dataset->getScoutUrl()));
        }

        foreach ($communityResults as $community) {
            $searchResults->push(new SearchResult($community, $community->name, $community->getScoutUrl()));
        }

        // Return a SearchResultCollection for compatibility with the view
        return new SearchResultCollection($searchResults);
    }
}
