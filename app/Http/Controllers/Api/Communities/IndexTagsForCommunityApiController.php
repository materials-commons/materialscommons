<?php

namespace App\Http\Controllers\Api\Communities;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tags\TagResource;
use App\Models\Community;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Tags\Tag;

class IndexTagsForCommunityApiController extends Controller
{
    public function __invoke(Request $request, Community $community)
    {
        abort_unless($this->canAccessCommunity($community), 403, "No such community");
        $community->load(['publishedDatasets']);

        $publishedDatasetIds = $community->publishedDatasets->pluck('id')->toArray();
        $tags = Tag::whereIn(
            'id',
            DB::table('taggables')
              ->select('tag_id')
              ->where('taggable_type', Dataset::class)
              ->whereIn('taggable_id', $publishedDatasetIds)
              ->get()
              ->pluck('tag_id')
              ->toArray()
        )
                   ->distinct()
                   ->get();
        return TagResource::collection($tags);
    }

    private function canAccessCommunity(Community $community)
    {
        if ($community->public) {
            return true;
        }

        return $community->owner_id === auth()->id();
    }
}
