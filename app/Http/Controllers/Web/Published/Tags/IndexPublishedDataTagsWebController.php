<?php

namespace App\Http\Controllers\Web\Published\Tags;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class IndexPublishedDataTagsWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $datasets = Dataset::with('tags')->whereNotNull('published_at')->select('tags', 'id')->get();
        $tags = [];
        foreach ($datasets as $ds) {
            foreach ($ds->tags as $tag) {
                if (isset($tags[$tag->name])) {
                    $count = $tags[$tag->name];
                    $count++;
                    $tags[$tag->name] = $count;
                } else {
                    $tags[$tag->name] = 1;
                }
            }
        }

        $tags = collect($tags)->sortKeys()->toArray();
        return view('public.tags.index', compact('tags'));
    }
}
