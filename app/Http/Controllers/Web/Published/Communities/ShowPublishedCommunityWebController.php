<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShowPublishedCommunityWebController extends Controller
{
    public function __invoke(Request $request, $communityId)
    {
        $community = Community::with(['publishedDatasets.owner', 'owner'])
                              ->findOrFail($communityId);
        abort_unless($community->public, 404, "No such community");
        $userDatasets = collect();
        if (Auth::check()) {
            // User is logged in so get published datasets that aren't in the community
            $userDatasets = $this->getPublishedDatasetsForUserNotInCommunity(auth()->user(), $community);
        }
        $tags = $this->getCommunityTags($community);
        $contributors = $this->getContributors($community);
        return view('public.communities.show', [
            'community'    => $community,
            'tags'         => $tags,
            'contributors' => $contributors,
            'userDatasets' => $userDatasets,
        ]);
    }

    private function getCommunityTags(Community $community)
    {
        $tags = [];
        foreach ($community->publishedDatasets as $ds) {
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
        foreach ($community->publishedDatasets as $ds) {
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
                } else {
                    $affiliations = "";
                }
                $contributors[$a] = $affiliations;
            }
        }
        return collect($contributors)->sortKeys()->toArray();
    }

    private function getPublishedDatasetsForUserNotInCommunity(User $user, Community $community)
    {
        return Dataset::with('communities')
                      ->whereNotNull('published_at')
                      ->where('owner_id', $user->id)
                      ->whereDoesntHave('communities', function ($query) use ($community) {
                          $query->where('communities.id', $community->id);
                      })
                      ->whereNotIn('id', function ($q) use ($community) {
                          $q->select('dataset_id')
                            ->from('community2ds_waiting_approval')
                            ->where('community_id', $community->id);
                      })
                      ->orderBy('name')
                      ->get();
    }
}
