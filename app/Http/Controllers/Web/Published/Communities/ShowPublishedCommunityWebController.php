<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Traits\Communities\CommunityOverview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowPublishedCommunityWebController extends Controller
{
    use CommunityOverview;

    public function __invoke(Request $request, $communityId)
    {
        $community = Community::with(['owner'])->findOrFail($communityId);
        abort_unless($community->public, 404, "No such community");

        $publishedDatasets = $community->publishedDatasets()
                                       ->with(['owner', 'tags'])
                                       ->withCount(['views', 'downloads'])
                                       ->orderByDesc('published_at')
                                       ->get();
        $community->setRelation('publishedDatasets', $publishedDatasets);
        $userDatasets = collect();
        if (Auth::check()) {
            // User is logged in so get published datasets that aren't in the community
            $userDatasets = $this->getPublishedDatasetsForUserNotInCommunity(auth()->user(), $community);
        }
        $tags             = $this->getCommunityTags($community);
        $contributors     = $this->getContributors($community);
        $contributorUsers = $this->getContributorUsers($community);
        return view('public.communities.show', [
            'community'        => $community,
            'tags'             => $tags,
            'contributors'     => $contributors,
            'contributorUsers' => $contributorUsers,
            'userDatasets'     => $userDatasets,
        ]);
    }
}
