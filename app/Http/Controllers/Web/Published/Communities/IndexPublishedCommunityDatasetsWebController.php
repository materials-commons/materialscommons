<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;

class IndexPublishedCommunityDatasetsWebController extends Controller
{
    public function __invoke($communityId)
    {
        $community = Community::with(['publishedDatasets.owner', 'owner'])->findOrFail($communityId);
        abort_unless($community->public, 404, 'No such community');
        return view('public.communities.show', compact('community'));
    }
}
