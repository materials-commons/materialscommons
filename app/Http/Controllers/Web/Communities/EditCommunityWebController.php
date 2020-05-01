<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;

class EditCommunityWebController extends Controller
{
    public function __invoke($communityId)
    {
        return view('app.communities.edit', [
            'community' => Community::with('datasets')->findOrFail($communityId),
            'user'      => auth()->user(),
        ]);
    }
}
