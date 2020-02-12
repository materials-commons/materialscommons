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
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class SearchAcrossProjectsAction
{
    public function __invoke($search, User $user)
    {
        $projectIds = $user->projects->map(function (Project $project) {
            return $project->id;
        })->toArray();
        return (new Search())
            ->registerModel(Project::class, function (ModelSearchAspect $modelSearchAspect) use ($projectIds) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->whereIn('id', $projectIds);
            })
            ->registerModel(File::class, function (ModelSearchAspect $modelSearchAspect) use ($projectIds) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->addSearchableAttribute('path')
                                  ->addSearchableAttribute('mime_type')
                                  ->addSearchableAttribute('media_type_description')
                                  ->with('project')
                                  ->whereIn('project_id', $projectIds);
            })
            ->registerModel(Experiment::class, function (ModelSearchAspect $modelSearchAspect) use ($projectIds) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->with('project')
                                  ->whereIn('project_id', $projectIds);
            })
            ->registerModel(Entity::class, function (ModelSearchAspect $modelSearchAspect) use ($projectIds) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->with('project')
                                  ->whereIn('project_id', $projectIds);
            })
            ->registerModel(Activity::class, function (ModelSearchAspect $modelSearchAspect) use ($projectIds) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->with('project')
                                  ->whereIn('project_id', $projectIds);
            })
            ->registerModel(Workflow::class, function (ModelSearchAspect $modelSearchAspect) use ($projectIds) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->addSearchableAttribute('workflow')
                                  ->with('project')
                                  ->whereIn('project_id', $projectIds);
            })
            ->registerModel(Dataset::class, function (ModelSearchAspect $modelSearchAspect) use ($projectIds) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->addSearchableAttribute('authors')
                                  ->with('project')
                                  ->whereIn('project_id', $projectIds);
            })
            ->registerModel(Community::class, function (ModelSearchAspect $modelSearchAspect) use ($projectIds) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->where('public', true);
            })
            ->search($search);
    }
}