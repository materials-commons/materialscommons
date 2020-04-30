<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class ShowPublishedCommunityRecommendedPracticesWebController extends Controller
{
    public function __invoke(Request $request, $communityId)
    {
        $community = Community::with(['files.owner', 'links.owner'])->findOrFail($communityId);
        abort_unless($community->public, 404, "No such community");
        return view('public.communities.show', ['community' => $community]);
    }
}
