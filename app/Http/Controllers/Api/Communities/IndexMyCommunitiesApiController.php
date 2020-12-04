<?php

namespace App\Http\Controllers\Api\Communities;

use App\Http\Controllers\Controller;
use App\Http\Resources\Communities\CommunityResource;
use App\Models\Community;
use Illuminate\Http\Request;

class IndexMyCommunitiesApiController extends Controller
{
    public function __invoke(Request $request)
    {
        $communities = Community::with(['owner'])
                                ->withCount(['datasets', 'links_count', 'files_count'])
                                ->where('owner_id', auth()->id())
                                ->get();
        return CommunityResource::collection($communities);
    }
}
