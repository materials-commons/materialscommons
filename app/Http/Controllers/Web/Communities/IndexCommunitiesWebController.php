<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexCommunitiesWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $communities = auth()->user()->communities()->get();
        return view('app.communities.index', compact('communities'));
    }
}
