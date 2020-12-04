<?php

namespace App\Http\Controllers\Api\Communities;

use App\Actions\Links\CreateLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Links\CreateUpdateLinkRequest;
use App\Http\Resources\Communities\CommunityResource;
use App\Models\Community;

class CreateLinkInCommunityApiController extends Controller
{
    public function __invoke(CreateUpdateLinkRequest $request, CreateLinkAction $createLinkAction, Community $community)
    {
        abort_unless($community->owner_id === auth()->id(), 403, "No such community");

        $validated = $request->validated();
        $link = $createLinkAction->execute($validated, auth()->id());
        $community->links()->attach($link);
        $community->load(['owner', 'datasets', 'links', 'files']);
        return new CommunityResource($community);
    }
}
