<?php

namespace App\Http\Controllers\Api\Communities;

use App\Http\Controllers\Controller;
use App\Http\Resources\Communities\CommunityResource;
use App\Models\Community;
use App\Models\Dataset;

class RemoveDatasetFromCommunityApiController extends Controller
{
    public function __invoke(Community $community, Dataset $dataset)
    {
        abort_unless($community->owner_id == auth()->id(), 404, "No such dataset");
        $count = $community->datasets()->where('id', $dataset->id)->count();
        if ($count !== 0) {
            $community->datasets()->detach($dataset->id);
        }

        $community->load(['owner', 'datasets', 'links', 'files']);
        return new CommunityResource($community);
    }
}
