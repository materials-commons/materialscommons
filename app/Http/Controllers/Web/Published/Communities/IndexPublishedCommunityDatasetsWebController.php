<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Traits\Communities\CommunityOverview;

class IndexPublishedCommunityDatasetsWebController extends Controller
{
    use CommunityOverview;

    public function __invoke($communityId)
    {
        $community = Community::with(['owner'])->findOrFail($communityId);
        abort_unless($community->public, 404, 'No such community');

        $publishedDatasets = $community->publishedDatasets()
                                       ->with(['owner', 'tags'])
                                       ->withCount(['views', 'downloads'])
                                       ->orderByDesc('published_at')
                                       ->get();
        $community->setRelation('publishedDatasets', $publishedDatasets);

        $tags             = $this->getCommunityTags($community);
        $contributors     = $this->getContributors($community);
        $contributorUsers = $this->getContributorUsers($community);

        return view('public.communities.show', compact('community', 'tags', 'contributors', 'contributorUsers'));
    }
}
