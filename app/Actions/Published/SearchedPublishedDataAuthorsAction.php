<?php

namespace App\Actions\Published;

use App\Models\Dataset;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class SearchedPublishedDataAuthorsAction
{
    public function __invoke($search)
    {
        $datasets = Dataset::whereNotNull('published_at')->get();
        $datasetIds = $datasets->map(function (Dataset $dataset) {
            return $dataset->id;
        })->toArray();

        return (new Search())
            ->registerModel(Dataset::class, function (ModelSearchAspect $modelSearchAspect) use ($datasetIds) {
                $modelSearchAspect->addSearchableAttribute('authors')
                                  ->whereIn('id', $datasetIds);
            })
            ->search($search);
    }
}