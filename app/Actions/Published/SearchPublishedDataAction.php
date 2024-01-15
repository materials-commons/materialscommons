<?php

namespace App\Actions\Published;

use App\Models\Community;
use App\Models\Dataset;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class SearchPublishedDataAction
{
    public function __invoke($search)
    {
        $datasets = Dataset::whereNotNull('published_at')->get();
        $datasetIds = $datasets->map(function (Dataset $dataset) {
            return $dataset->id;
        })->toArray();

        return (new Search())
            ->registerModel(Dataset::class, function (ModelSearchAspect $modelSearchAspect) use ($datasetIds) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->addSearchableAttribute('authors')
                                  ->whereIn('id', $datasetIds);
            })
            ->registerModel(Community::class, function (ModelSearchAspect $modelSearchAspect) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->where('public', true);
            })
            ->limitAspectResults(10)
            ->search($search);
    }
}