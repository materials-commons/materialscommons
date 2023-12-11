<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use Illuminate\Http\Request;

class SearchCommunityPublishedDatasetsForAuthorWebController extends Controller
{
    public function __invoke(Request $request, Community $community)
    {
        $author = $request->input('author');
        $datasetsFromCommunity = Dataset::with(['owner', 'tags', 'rootDir'])
                                        ->withCount('views', 'downloads')
                                        ->whereNotNull('published_at')
                                        ->whereHas('communities', function ($query) use ($community) {
                                            $query->where('communities.id', $community->id);
                                        })
                                        ->where('authors', 'like', '%'.$author.'%')
                                        ->get();

        $datasetsNotFromCommunity = Dataset::with(['owner', 'tags', 'rootDir'])
                                           ->withCount('views', 'downloads')
                                           ->whereNotNull('published_at')
                                           ->whereDoesntHave('communities', function ($query) use ($community) {
                                               $query->where('communities.id', $community->id);
                                           })
                                           ->where('authors', 'like', '%'.$author.'%')
                                           ->get();

        return view('public.communities.datasets_for_author', [
            'author'                   => $author,
            'community'                => $community,
            'datasetsFromCommunity'    => $datasetsFromCommunity,
            'datasetsNotFromCommunity' => $datasetsNotFromCommunity,
        ]);
    }
}
