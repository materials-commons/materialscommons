<?php

namespace App\Http\Controllers\Api\Communities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Communities\Links\LinkInCommunity;
use App\Http\Resources\Communities\CommunityResource;
use App\Models\Community;
use App\Models\Link;

class DeleteLinkFromCommunityApiController extends Controller
{
    use LinkInCommunity;

    public function __invoke(Community $community, Link $link)
    {
        abort_unless($community->owner_id === auth()->id(), 403, "No such community");
        abort_unless($this->linkInCommunity($community, $link), 404, "No such link in community");
        $link->delete();
        $community->load(['owner', 'datasets', 'links', 'files']);
        return new CommunityResource($community);
    }
}
