<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Http\Requests\Communities\CreateCommunityRequest;
use App\Models\Community;

class StoreCommunityWebController extends Controller
{
    public function __invoke(CreateCommunityRequest $request)
    {
        $validated = $request->validated();
        $validated['owner_id'] = auth()->id();
        Community::create($validated);
        return redirect(route('communities.index'));
    }
}
