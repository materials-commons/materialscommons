<?php

namespace App\Actions\Projects;

use App\Models\Activity;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\File;
use App\Models\Workflow;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class SearchProjectAction
{
    public function __invoke($search, $projectId)
    {
        return (new Search())
            ->registerModel(File::class, function (ModelSearchAspect $modelSearchAspect) use ($projectId) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->addSearchableAttribute('path')
                                  ->addSearchableAttribute('media_type_description')
                                  ->where('project_id', $projectId);
            })
            ->registerModel(Entity::class, function (ModelSearchAspect $modelSearchAspect) use ($projectId) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->where('project_id', $projectId);
            })
            ->registerModel(Activity::class, function (ModelSearchAspect $modelSearchAspect) use ($projectId) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->where('project_id', $projectId);
            })
            ->registerModel(Workflow::class, function (ModelSearchAspect $modelSearchAspect) use ($projectId) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->addSearchableAttribute('workflow')
                                  ->where('project_id', $projectId);
            })
            ->registerModel(Dataset::class, function (ModelSearchAspect $modelSearchAspect) use ($projectId) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->addSearchableAttribute('authors')
                                  ->where('project_id', $projectId);
            })
            ->registerModel(Community::class, function (ModelSearchAspect $modelSearchAspect) use ($projectId) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->where('public', 'true');
            })
            ->search($search);
    }
}