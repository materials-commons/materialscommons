<?php

namespace App\Http\Controllers\Web\Communities\Links;

use App\Http\Controllers\Controller;
use App\Http\Requests\Links\CreateUpdateLinkRequest;
use App\Models\Community;
use App\Models\Link;

class UpdateLinkForCommunityWebController extends Controller
{
    use LinkInCommunity;

    public function __invoke(CreateUpdateLinkRequest $request, Community $community, Link $link)
    {
        abort_unless($this->linkInCommunity($community, $link), 404, "No such link in community");

        $validated = $request->validated();
        $link->update($validated);
        return redirect(route('communities.links.edit', [$community]));
    }
}
