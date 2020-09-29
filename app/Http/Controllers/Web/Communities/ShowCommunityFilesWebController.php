<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;

class ShowCommunityFilesWebController extends Controller
{
    public function __invoke($communityId)
    {
        return view('app.communities.show', [
            'community' => Community::with(['files.owner'])->findOrFail($communityId),
        ]);
    }
}
