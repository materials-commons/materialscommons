<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class IndexPublishedCommunitiesWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $communities = Community::with('owner')->withCount('datasets')
                                ->where('public', true)->get();
        return view('public.community.index', compact('communities'));
    }
}
