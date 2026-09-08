<?php

namespace App\Actions\Published;

use App\Models\Community;
use App\Models\Dataset;
use Illuminate\Support\Collection;

class SearchPublishedDataAction
{
    public function __invoke($search)
    {
        // Search published datasets using Laravel Scout
        $datasetResults = Dataset::search($search)
            ->whereNotNull('published_at')
            ->take(10)
            ->get();

        // Search public communities using Laravel Scout
        $communityResults = Community::search($search)
            ->where('public', true)
            ->take(10)
            ->get();

        // Combine results into a single collection
        $searchResults = new Collection([
            $datasetResults, $communityResults
        ]);

        return $searchResults->collapse();
    }
}
