<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;

class DeleteCommunityWebController extends Controller
{
    public function __invoke(Community $community)
    {
        return view('app.communities.delete', compact('community'));
    }
}
