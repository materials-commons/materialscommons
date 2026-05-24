@props([
    'communities' => collect(),
    'datasets' => collect(),
    'listedInDatasets' => collect(),
])

@php
    $communities = collect($communities);
    $datasets = collect($datasets);
    $listedInDatasets = collect($listedInDatasets);
    $currentUserId = auth()->id();

    $communityRows = $communities->map(function ($community) use ($currentUserId) {
        $publishedDatasets = collect($community->publishedDatasets ?? collect());
        $tags = [];
        $contributors = [];

        foreach ($publishedDatasets as $dataset) {
            foreach (collect($dataset->tags ?? collect()) as $tag) {
                $tags[$tag->name] = ($tags[$tag->name] ?? 0) + 1;
            }

            foreach (collect($dataset->ds_authors ?? collect()) as $author) {
                $name = trim((string) data_get($author, 'name'));

                if (blank($name)) {
                    continue;
                }

                $contributors[$name] = data_get($author, 'affiliations');
            }
        }

        arsort($tags);
        ksort($contributors);

        $views = $publishedDatasets->sum('views_count');
        $downloads = $publishedDatasets->sum('downloads_count');

        return [
            'community' => $community,
            'name' => $community->name,
            'url' => route('public.communities.show', $community),
            'datasets_url' => route('public.communities.datasets.index', $community),
            'owner_name' => $community->owner?->name,
            'owner_url' => $community->owner ? route('public.authors.show', $community->owner) : null,
            'owner_affiliations' => $community->owner?->affiliations,
            'is_owner' => (int) $community->owner_id === (int) $currentUserId,
            'is_public' => (bool) $community->public,
            'dataset_count' => $publishedDatasets->count(),
            'views_count' => $views,
            'downloads_count' => $downloads,
            'links_count' => collect($community->links ?? collect())->count(),
            'files_count' => collect($community->files ?? collect())->count(),
            'topic_count' => count($tags),
            'contributor_count' => count($contributors),
            'tags' => $tags,
            'contributors' => $contributors,
            'summary' => $community->summary,
            'description' => $community->description,
            'updated_at' => $community->updated_at,
            'score' => ($publishedDatasets->count() * 5) + $views + ($downloads * 2),
        ];
    })->sortByDesc('score')->values();

    $ownedCommunities = $communityRows->where('is_owner', true)->values();
    $appearsInCommunities = $communityRows->where('is_owner', false)->values();
    $publicCommunities = $communityRows->where('is_public', true)->values();

    $totalDatasets = $communityRows->sum('dataset_count');
    $totalViews = $communityRows->sum('views_count');
    $totalDownloads = $communityRows->sum('downloads_count');
    $totalLinks = $communityRows->sum('links_count');
    $totalFiles = $communityRows->sum('files_count');

    $topicRows = $communityRows
        ->flatMap(function ($row) {
            return collect($row['tags'])->map(fn($count, $tag) => [
                'tag' => $tag,
                'count' => $count,
                'community' => $row['community'],
                'community_name' => $row['name'],
            ]);
        })
        ->groupBy(fn($row) => mb_strtolower($row['tag']))
        ->map(function ($items) {
            $first = $items->first();

            return [
                'tag' => $first['tag'],
                'count' => $items->sum('count'),
                'community_count' => $items->pluck('community')->pluck('id')->unique()->count(),
            ];
        })
        ->sortByDesc('count')
        ->values();

    $contributorRows = $communityRows
        ->flatMap(function ($row) {
            return collect($row['contributors'])->map(fn($affiliation, $name) => [
                'name' => $name,
                'affiliation' => $affiliation,
                'community_name' => $row['name'],
            ]);
        })
        ->groupBy(fn($row) => mb_strtolower($row['name']))
        ->map(function ($items) {
            $first = $items->first();

            return [
                'name' => $first['name'],
                'affiliation' => $first['affiliation'],
                'community_count' => $items->pluck('community_name')->unique()->count(),
            ];
        })
        ->sortByDesc('community_count')
        ->values();

    $topCommunities = $communityRows->take(10)->values();
    $topTopics = $topicRows->take(12)->values();
    $topContributors = $contributorRows->take(10)->values();
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.communities.summary-card
            label="Communities"
            :value="$communityRows->count()"
            hint="related to your work"
            color="primary"
            icon="fas fa-layer-group"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.communities.summary-card
            label="Owned"
            :value="$ownedCommunities->count()"
            hint="organized by you"
            color="success"
            icon="fas fa-user-tie"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.communities.summary-card
            label="Dataset Links"
            :value="$totalDatasets"
            hint="published datasets"
            color="info"
            icon="fas fa-database"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.communities.summary-card
            label="Views"
            :value="$totalViews"
            hint="community datasets"
            color="secondary"
            icon="fas fa-eye"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.communities.summary-card
            label="Downloads"
            :value="$totalDownloads"
            hint="community datasets"
            color="warning"
            icon="fas fa-download"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.communities.summary-card
            label="Resources"
            :value="$totalLinks + $totalFiles"
            hint="links + files"
            color="danger"
            icon="fas fa-paperclip"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-7">
        <x-dashboard.my-research.communities.charts.top-communities
            :communities="$topCommunities"
        />
    </div>

    <div class="col-12 col-xl-5">
        <x-dashboard.my-research.communities.analytics
            :community-rows="$communityRows"
            :owned-communities="$ownedCommunities"
            :appears-in-communities="$appearsInCommunities"
            :public-communities="$publicCommunities"
            :top-topics="$topTopics"
            :top-contributors="$topContributors"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-5">
        <x-dashboard.my-research.communities.charts.topic-distribution
            :topics="$topTopics"
        />
    </div>

    <div class="col-12 col-xl-7">
        <div class="row g-3">
            @forelse($communityRows->take(2) as $row)
                <div class="col-12">
                    <x-dashboard.my-research.communities.community-card
                        :row="$row"
                    />
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3 background-white">
                            <p class="text-muted mb-0">No communities found for your research yet.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<x-dashboard.my-research.communities.table
    :community-rows="$communityRows"
/>
