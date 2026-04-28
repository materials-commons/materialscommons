<?php

namespace App\Traits\Communities;

use App\Models\Community;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Support\Str;
use function blank;
use function collect;
use function is_null;

trait CommunityOverview
{
    private function getCommunityTags(Community $community): array
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

    private function getContributors(Community $community): array
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

    private function getContributorUsers(Community $community): \Illuminate\Support\Collection
    {
        // Collect name → email from ds_authors across all published datasets
        $emailByName = [];
        foreach ($community->publishedDatasets as $ds) {
            if (is_null($ds->ds_authors)) {
                continue;
            }
            foreach ($ds->ds_authors as $author) {
                $name = Str::of($author['name'] ?? '')->trim()->__toString();
                if (blank($name) || isset($emailByName[$name])) {
                    continue;
                }
                $emailByName[$name] = blank($author['email'] ?? '') ? null : trim($author['email']);
            }
        }

        if (empty($emailByName)) {
            return collect();
        }

        $names  = array_keys($emailByName);
        $emails = array_values(array_filter($emailByName));

        $users = User::where(function ($q) use ($names, $emails) {
            $q->whereIn('name', $names);
            if (!empty($emails)) {
                $q->orWhereIn('email', $emails);
            }
        })->get(['id', 'name', 'slug', 'email']);

        $byName  = $users->keyBy('name');
        $byEmail = $users->keyBy('email');

        // Build lookup keyed by contributor name (name-match takes priority over email-match)
        $lookup = collect();
        foreach ($emailByName as $contribName => $email) {
            if ($byName->has($contribName)) {
                $lookup->put($contribName, $byName->get($contribName));
            } elseif ($email && $byEmail->has($email)) {
                $lookup->put($contribName, $byEmail->get($email));
            }
        }

        return $lookup;
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