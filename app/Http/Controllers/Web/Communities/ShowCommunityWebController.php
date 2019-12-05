<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;

class ShowCommunityWebController extends Controller
{
    public function __invoke($communityId)
    {
        $community = Community::with('datasets.owner')->findOrFail($communityId);
        return view('app.communities.show', compact('community'));
    }
}
