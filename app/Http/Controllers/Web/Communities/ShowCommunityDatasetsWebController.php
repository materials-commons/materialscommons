<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;

class ShowCommunityDatasetsWebController extends Controller
{
    public function __invoke($communityId)
    {
        return view('app.communities.show', [
            'community' => Community::with(['datasets.owner'])->findOrFail($communityId),
        ]);
    }
}
