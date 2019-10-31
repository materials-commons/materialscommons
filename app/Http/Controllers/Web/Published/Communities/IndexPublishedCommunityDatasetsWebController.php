<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;

class IndexPublishedCommunityDatasetsWebController extends Controller
{
    public function __invoke($communityId)
    {
        $community = Community::with(['datasets', 'owner'])->findOrFail($communityId);
        return view('public.community.datasets', compact('community'));
    }
}
