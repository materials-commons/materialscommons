<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;

class EditCommunityLinksWebController extends Controller
{
    public function __invoke($communityId)
    {
        return view('app.communities.edit', [
            'community' => Community::with('links')->findOrFail($communityId),
            'user'      => auth()->user(),
        ]);
    }
}
