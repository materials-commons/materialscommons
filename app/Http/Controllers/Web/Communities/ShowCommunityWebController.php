<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Traits\Communities\CommunityOverview;
use function view;

class ShowCommunityWebController extends Controller
{
    use CommunityOverview;

    public function __invoke($communityId)
    {
        $community = Community::with(['publishedDatasets.owner', 'publishedDatasets.tags', 'owner'])
                              ->findOrFail($communityId);
        $tags = $this->getCommunityTags($community);
        $contributors = $this->getContributors($community);
//        $community = Community::with('datasets.owner')->findOrFail($communityId);
        return view('app.communities.show', [
            'community'    => $community,
            'tags'         => $tags,
            'contributors' => $contributors,
        ]);
    }
}
