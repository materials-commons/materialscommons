<?php

namespace App\Http\Controllers\Web\Communities\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use Illuminate\Http\Request;

class UpdateCommunityDatasetsWebController extends Controller
{
    public function __invoke(Request $request, Community $community)
    {
        abort_unless($community->owner_id == auth()->id(), 404, "No such community");
        return view('app.communities.update-datasets', [
            'community' => $community,
            'datasets'  => Dataset::with('communities')->whereNotNull('published_at')->get(),
            'user'      => auth()->user(),
        ]);
    }
}
