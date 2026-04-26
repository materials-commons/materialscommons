<?php

namespace App\Http\Controllers\Web\Published\Authors;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndexPublishedAuthorsWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $datasets = Dataset::whereNotNull('published_at')->get(['ds_authors', 'published_at']);

        $authors = [];
        foreach ($datasets as $ds) {
            if (is_null($ds->ds_authors)) {
                continue;
            }
            $pubDate = $ds->published_at;
            foreach ($ds->ds_authors as $author) {
                $name = Str::of($author['name'])->trim()->__toString();
                if ($name === '') {
                    continue;
                }
                if (!isset($authors[$name])) {
                    $authors[$name] = ['count' => 0, 'latest' => null, 'since' => null];
                }
                $authors[$name]['count']++;
                if ($pubDate) {
                    if (is_null($authors[$name]['latest']) || $pubDate > $authors[$name]['latest']) {
                        $authors[$name]['latest'] = $pubDate;
                    }
                    if (is_null($authors[$name]['since']) || $pubDate < $authors[$name]['since']) {
                        $authors[$name]['since'] = $pubDate;
                    }
                }
            }
        }

        $authors = collect($authors)
            ->sortKeys()
            ->toArray();

        return view('public.authors.index', compact('authors'));
    }
}
