<?php

namespace App\Actions\Projects;

use App\Models\Activity;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Workflow;
use Illuminate\Support\Collection;
use Spatie\Searchable\SearchResult;
use Spatie\Searchable\SearchResultCollection;

class SearchProjectAction
{
    public function __invoke($search, $projectId)
    {
        // Search each model type with Laravel Scout
        $fileResults = File::search($search)
            ->query(function ($query) {
                return $query->with('directory');
            })
            ->where('dataset_id', null)
            ->where('deleted_at', null)
            ->where('current', true)
            ->where('project_id', $projectId)
            ->take(10)
            ->get();

        $experimentResults = Experiment::search($search)
            ->where('project_id', $projectId)
            ->take(10)
            ->get();

        $entityResults = Entity::search($search)
            ->query(function ($query) {
                return $query->with('experiments');
            })
            ->where('project_id', $projectId)
            ->take(10)
            ->get();

        $activityResults = Activity::search($search)
            ->query(function ($query) {
                return $query->with('experiments');
            })
            ->where('project_id', $projectId)
            ->take(10)
            ->get();

//        $workflowResults = Workflow::search($search)
//            ->where('project_id', $projectId)
//            ->take(10)
//            ->get();

        $datasetResults = Dataset::search($search)
            ->where('project_id', $projectId)
            ->take(10)
            ->get();

        $communityResults = Community::search($search)
            ->where('public', true)
            ->take(10)
            ->get();

        // Convert to SearchResult objects for compatibility with the view
        $searchResults = new Collection([
            $fileResults, $experimentResults, $entityResults, $activityResults, $datasetResults, $communityResults
        ]);

        return $searchResults->collapse();

//        foreach ($fileResults as $file) {
//            $searchResults->push($file);
//        }
//
//        foreach ($experimentResults as $experiment) {
//            $searchResults->push(new SearchResult($experiment, $experiment->name, $experiment->getScoutUrl()));
//        }
//
//        foreach ($entityResults as $entity) {
//            $searchResults->push(new SearchResult($entity, $entity->name, $entity->getScoutUrl()));
//        }
//
//        foreach ($activityResults as $activity) {
//            $searchResults->push(new SearchResult($activity, $activity->name, $activity->getScoutUrl()));
//        }
//
////        foreach ($workflowResults as $workflow) {
////            $searchResults->push(new SearchResult($workflow, $workflow->name, $workflow->getScoutUrl()));
////        }
//
//        foreach ($datasetResults as $dataset) {
//            $searchResults->push(new SearchResult($dataset, $dataset->name, $dataset->getScoutUrl()));
//        }
//
//        foreach ($communityResults as $community) {
//            $searchResults->push(new SearchResult($community, $community->name, $community->getScoutUrl()));
//        }
//
//        // Return a SearchResultCollection for compatibility with the view
//        return new SearchResultCollection($searchResults);
    }
}
