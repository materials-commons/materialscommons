<?php

namespace App\Http\Controllers\Web\Communities\Links;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Link;

class DestroyLinkForCommunityWebController extends Controller
{
    use LinkInCommunity;

    public function __invoke(Community $community, Link $link)
    {
        abort_unless($this->linkInCommunity($community, $link), 404, "No such link in community");
        $link->delete();
        return redirect(route('communities.links.edit', ['community' => $community]));
    }
}
