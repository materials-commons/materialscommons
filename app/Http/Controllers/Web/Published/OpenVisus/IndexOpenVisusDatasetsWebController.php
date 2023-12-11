<?php

namespace App\Http\Controllers\Web\Published\OpenVisus;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;
use function compact;
use function view;

class IndexOpenVisusDatasetsWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $tag = $request->input('tag');
        $datasets = Dataset::withAnyTags([$tag])->with('tags')->whereNotNull('published_at')->get();
        return view('public.tags.search', compact('datasets', 'tag'));
    }
}
