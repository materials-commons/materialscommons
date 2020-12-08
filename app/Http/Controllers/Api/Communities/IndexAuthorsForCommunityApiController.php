<?php

namespace App\Http\Controllers\Api\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndexAuthorsForCommunityApiController extends Controller
{
    public function __invoke(Request $request, Community $community)
    {
        abort_unless($community->userCanAccess(auth()->id()), 403, "No such community");
        $community->load(['publishedDatasets']);
        return $this->getCommunityPublishedDatasetAuthors($community);
    }

    private function getCommunityPublishedDatasetAuthors(Community $community)
    {
        $merged = collect();
        $community->publishedDatasets
            ->pluck('authors')
            ->map(function ($authors) {
                return Str::of($authors)->explode('; ')->map(function ($author) {
                    return Str::of($author)->before('(')->trim()->__toString();
                });
            })
            ->each(function ($authors) use (&$merged) {
                $merged = $merged->merge($authors);
            });

        return $merged->filter(function ($author) {
            return !blank($author);
        })
                      ->unique()
                      ->values();
    }
}
