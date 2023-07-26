<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;

class ShowCommunityDatasetsWebController extends Controller
{
    public function __invoke($communityId)
    {
        $community = Community::with(['publishedDatasets.owner', 'owner'])
                              ->findOrFail($communityId);

        return view('app.communities.show', [
            'community' => $community,
        ]);
    }
}
