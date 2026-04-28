<?php

namespace App\Http\Controllers\Web\Published\Tags;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Http\Request;
use function blank;
use function trim;

class SearchPublishedDataTagsWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $tag      = $request->input('tag');
        $datasets = Dataset::withAnyTags([$tag])
                           ->with('tags')
                           ->withCount(['views', 'downloads'])
                           ->whereNotNull('published_at')
                           ->orderByDesc('published_at')
                           ->get();

        // Related tags — co-occurring tags across the same datasets, excluding the current tag
        $relatedTags = [];
        foreach ($datasets as $ds) {
            foreach ($ds->tags as $t) {
                if ($t->name === $tag) {
                    continue;
                }
                $relatedTags[$t->name] = ($relatedTags[$t->name] ?? 0) + 1;
            }
        }
        arsort($relatedTags);

        // Top contributing authors
        $authorCounts = [];
        foreach ($datasets as $ds) {
            if (!$ds->ds_authors) {
                continue;
            }
            foreach ($ds->ds_authors as $author) {
                $name = trim($author['name'] ?? '');
                if (blank($name)) {
                    continue;
                }
                $authorCounts[$name] = ($authorCounts[$name] ?? 0) + 1;
            }
        }
        arsort($authorCounts);
        $topAuthors = array_slice($authorCounts, 0, 15, true);

        // Resolve MC accounts for top authors (by name)
        $topAuthorUsers = User::whereIn('name', array_keys($topAuthors))
                              ->get(['id', 'name', 'slug'])
                              ->keyBy('name');

        // Publication timeline (by year-month)
        $timeline = [];
        foreach ($datasets as $ds) {
            if (!$ds->published_at) {
                continue;
            }
            $month            = $ds->published_at->format('Y-m');
            $timeline[$month] = ($timeline[$month] ?? 0) + 1;
        }
        ksort($timeline);

        return view('public.tags.search', compact(
            'datasets', 'tag', 'relatedTags', 'topAuthors', 'topAuthorUsers', 'timeline'
        ));
    }
}
