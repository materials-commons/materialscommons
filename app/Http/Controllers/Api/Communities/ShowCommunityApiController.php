<?php

namespace App\Http\Controllers\Api\Communities;

use App\Http\Controllers\Controller;
use App\Http\Resources\Communities\CommunityResource;
use App\Models\Community;
use Illuminate\Http\Request;

class ShowCommunityApiController extends Controller
{
    public function __invoke(Request $request, Community $community)
    {
        abort_unless($this->canAccessCommunity($community), 403, "No such community {$community->id}");

        $community->load(['owner', 'datasets', 'links', 'files']);
        return new CommunityResource($community);
    }

    private function canAccessCommunity(Community $community)
    {
        // If public community then anybody can access it.
        if ($community->public) {
            return true;
        }

        // Otherwise users can only see communities they own.
        return ($community->owner_id === auth()->id());
    }
}
