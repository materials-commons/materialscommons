<?php

namespace App\Http\Controllers\Api\Published;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tags\TagResource;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Tags\Tag;

class IndexPublishedDatasetTagsApiController extends Controller
{
    public function __invoke(Request $request)
    {
        $publishedDatasetIds = Dataset::whereNotNull('published_at')
                                      ->pluck('id')
                                      ->toArray();
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
}
