<?php

namespace App\Http\Controllers\Web\Published\Tags;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class SearchPublishedDataTagsWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $tag = $request->input('tag');
        $datasets = Dataset::withAnyTags([$tag])->with('tags')->whereNotNull('published_at')->get();
        return view('public.tags.search', compact('datasets', 'tag'));
    }
}
