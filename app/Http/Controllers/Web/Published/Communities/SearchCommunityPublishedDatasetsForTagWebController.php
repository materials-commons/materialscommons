<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use Illuminate\Http\Request;

class SearchCommunityPublishedDatasetsForTagWebController extends Controller
{
    public function __invoke(Request $request, Community $community)
    {
        $tag = $request->input('tag');
        $datasetsFromCommunity = Dataset::withAnyTags([$tag])
                                        ->with(['owner', 'tags', 'rootDir'])
                                        ->withCount('views', 'downloads')
                                        ->whereHas('communities', function ($query) use ($community) {
                                            $query->where('communities.id', $community->id);
                                        })
                                        ->whereNotNull('published_at')
                                        ->get();
        $datasetsNotFromCommunity = Dataset::withAnyTags([$tag])
                                           ->with(['owner', 'tags', 'rootDir'])
                                           ->withCount('views', 'downloads')
                                           ->whereDoesntHave('communities', function ($query) use ($community) {
                                               $query->where('communities.id', $community->id);
                                           })
                                           ->whereNotNull('published_at')
                                           ->get();

        return view('public.communities.datasets_for_tag', [
            'tag'                      => $tag,
            'community'                => $community,
            'datasetsFromCommunity'    => $datasetsFromCommunity,
            'datasetsNotFromCommunity' => $datasetsNotFromCommunity,
        ]);

    }
}
