<?php

namespace App\Http\Controllers\Web\Communities\Links;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class CreateLinkForCommunityWebController extends Controller
{
    public function __invoke(Request $request, Community $community)
    {
        return view('app.communities.links.create', compact('community'));
    }
}
