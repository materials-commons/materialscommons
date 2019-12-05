<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Http\Requests\Communities\UpdateCommunityRequest;
use App\Models\Community;

class UpdateCommunityWebController extends Controller
{
    public function __invoke(UpdateCommunityRequest $request, Community $community)
    {
        $validated = $request->validated();
        $community->update($validated);
        return redirect(route('communities.index'));
    }
}
