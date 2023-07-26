<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class IndexDatasetsAwaitingApprovalWebController extends Controller
{
    public function __invoke(Request $request, Community $community)
    {
        $community->load('datasetsWaitingForApproval');
        return view('app.communities.show', [
            'community' => $community,
        ]);
    }
}
