<?php

namespace App\Http\Controllers\Web\Communities\Links;

use App\Actions\Links\CreateLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Links\CreateLinkRequest;
use App\Models\Community;

class StoreLinkForCommunityWebController extends Controller
{
    public function __invoke(CreateLinkRequest $request, CreateLinkAction $createLinkAction, Community $community)
    {
        $validated = $request->validated();
        $link = $createLinkAction->execute($validated, auth()->id());
        $community->links()->attach($link);
        return redirect(route('communities.links.edit', ['community' => $community]));
    }
}
