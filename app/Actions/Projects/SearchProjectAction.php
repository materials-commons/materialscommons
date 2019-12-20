<?php

namespace App\Actions\Projects;

use App\Models\Activity;
use App\Models\Entity;
use App\Models\File;
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
            })->search($search);
    }
}