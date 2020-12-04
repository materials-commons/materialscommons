<?php

namespace App\Http\Controllers\Api\Communities;

use App\Http\Controllers\Controller;
use App\Http\Requests\Communities\CreateCommunityRequest;
use App\Http\Resources\Communities\CommunityResource;
use App\Models\Community;

class CreateCommunityApiController extends Controller
{
    public function __invoke(CreateCommunityRequest $request)
    {
        $validated = $request->validated();
        $validated['owner_id'] = auth()->id();
        $community = Community::create($validated);
        return new CommunityResource($community);
    }
}
