<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;

class DestroyCommunityWebController extends Controller
{
    public function __invoke(Community $community)
    {
        $community->delete();
        return redirect(route('communities.index'));
    }
}
