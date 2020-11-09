<?php

namespace App\Actions\Published;

use App\Models\Dataset;

class SearchedPublishedDataAuthorsAction
{
    public function __invoke($search)
    {
        return Dataset::withCount('views', 'downloads')
                      ->whereNotNull('published_at')
                      ->where('authors', 'like', '%'.$search.'%')
                      ->get();
    }
}