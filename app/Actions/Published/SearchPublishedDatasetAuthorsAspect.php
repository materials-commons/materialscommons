<?php

namespace App\Actions\Published;

use App\Models\Dataset;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Searchable\SearchAspect;

class SearchPublishedDatasetAuthorsAspect extends SearchAspect
{
    public function getResults(string $term): Collection
    {
        return Dataset::whereNotNull('published_at')->get()->filter(function (Dataset $ds) use ($term) {
            return Str::contains($ds->authors, $term);
        });
    }
}