<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShowPublishedCommunityWebController extends Controller
{
    public function __invoke(Request $request, $communityId)
    {
        $community = Community::with(['datasets.owner', 'owner'])->findOrFail($communityId);
        abort_unless($community->public, 404, "No such community");
        $tags = $this->getCommunityTags($community);
        $contributors = $this->getContributors($community);
        return view('public.communities.show', [
            'community'    => $community,
            'tags'         => $tags,
            'contributors' => $contributors,
        ]);
    }

    private function getCommunityTags(Community $community)
    {
        $tags = [];
        foreach ($community->datasets as $ds) {
            foreach ($ds->tags as $tag) {
                if (isset($tags[$tag->name])) {
                    $count = $tags[$tag->name];
                    $count++;
                    $tags[$tag->name] = $count;
                } else {
                    $tags[$tag->name] = 1;
                }
            }
        }

        return collect($tags)->sortKeys()->toArray();
    }

    private function getContributors(Community $community)
    {
        $contributors = [];
        foreach ($community->datasets as $ds) {
            if (is_null($ds->ds_authors)) {
                continue;
            }
            foreach ($ds->ds_authors as $author) {
                if (blank($author['name'])) {
                    continue;
                }
                $a = Str::of($author['name'])->trim()->__toString();
                if (!blank($author['affiliations'])) {
                    $affiliations = Str::of($author['affiliations'])->trim()->__toString();
                    $a = "{$a} ({$affiliations})";
                }
                $contributors[$a] = 1;
            }
        }
        return collect($contributors)->sortKeys()->toArray();
    }
}
