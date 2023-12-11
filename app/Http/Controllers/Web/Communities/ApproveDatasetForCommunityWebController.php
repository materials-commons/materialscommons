<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApproveDatasetForCommunityWebController extends Controller
{
    public function __invoke(Request $request, Community $community, Dataset $dataset)
    {
        DB::transaction(function () use ($community, $dataset) {
            $community->publishedDatasets()->syncWithoutDetaching($dataset);
            $community->datasetsWaitingForApproval()->detach($dataset);
        });
        flash("Dataset '{$dataset->name}' added to community.")->success();
        return redirect(route('communities.waiting-approval.index', [$community]));
    }
}
