<?php

namespace App\Http\Controllers\Api\Communities;

use App\Http\Controllers\Controller;
use App\Http\Resources\Communities\CommunityResource;
use App\Models\Community;
use Illuminate\Http\Request;

class IndexPublicCommunitiesApiController extends Controller
{

    public function __invoke(Request $request)
    {
        $communities = Community::with(['owner'])
                                ->withCount(['datasets', 'links', 'files'])
                                ->where('public', true)
                                ->get();
        return CommunityResource::collection($communities);
    }
}
