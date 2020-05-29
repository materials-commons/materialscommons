<?php

namespace App\Http\Controllers\Web\Communities\Links;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Link;

class DeleteLinkForCommunityWebController extends Controller
{
    use LinkInCommunity;

    public function __invoke(Community $community, Link $link)
    {
        abort_unless($this->linkInCommunity($community, $link), 404, "No such link in community");
        return view('app.communities.links.delete', compact('community', 'link'));
    }
}
