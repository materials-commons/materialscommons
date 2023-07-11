<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use Illuminate\Http\Request;

class SearchCommunityForDatasetsMatchingTagWebController extends Controller
{
    public function __invoke(Request $request, Community $community)
    {
        $tag = $request->input('tag');
        $datasets = Dataset::withAnyTags([$tag])
                           ->with('tags')
                           ->whereNotNull('published_at')
                           ->whereIn('id', function ($q) use ($community) {
                               $q->select('dataset_id')
                                 ->from('community2dataset')
                                 ->where('community_id', $community->id);
                           })
                           ->get();
        return view('public.tags.search', [
            'datasets' => $datasets,
            'tag'      => $tag,
        ]);
    }
}
