<?php

namespace App\Http\Controllers\Web\Published\Tags;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class IndexPublishedDataTagsWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $datasets = Dataset::with('tags')->whereNotNull('published_at')->get(['id', 'published_at']);

        $excludedTags = ['OpenVisus', 'OpenVisus-Commons-Import'];

        $tags = [];
        foreach ($datasets as $ds) {
            $pubDate = $ds->published_at;
            foreach ($ds->tags as $tag) {
                $name = $tag->name;
                if (in_array($name, $excludedTags)) {
                    continue;
                }
                if (!isset($tags[$name])) {
                    $tags[$name] = ['count' => 0, 'latest' => null, 'since' => null];
                }
                $tags[$name]['count']++;
                if ($pubDate) {
                    if (is_null($tags[$name]['latest']) || $pubDate > $tags[$name]['latest']) {
                        $tags[$name]['latest'] = $pubDate;
                    }
                    if (is_null($tags[$name]['since']) || $pubDate < $tags[$name]['since']) {
                        $tags[$name]['since'] = $pubDate;
                    }
                }
            }
        }

        $tags = collect($tags)->sortKeys()->toArray();

        return view('public.tags.index', compact('tags'));
    }
}
