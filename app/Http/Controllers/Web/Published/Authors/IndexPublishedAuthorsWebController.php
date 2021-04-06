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
        // Not the most efficient way, but good enough for now.
        $datasets = Dataset::whereNotNull('published_at')->get();
        $authors = [];
        foreach ($datasets as $ds) {
            if (is_null($ds->ds_authors)) {
                continue;
            }
            foreach ($ds->ds_authors as $author) {
                $author = Str::of($author['name'])->trim()->__toString();
                if (isset($authors[$author])) {
                    $count = $authors[$author];
                    $count++;
                    $authors[$author] = $count;
                } else {
                    $authors[$author] = 1;
                }
            }
        }

        $authors = collect($authors)
            ->filter(function ($value, $key) {
                return $key !== '';
            })
            ->sortKeys()
            ->toArray();
        return view('public.authors.index', compact('authors'));
    }
}
