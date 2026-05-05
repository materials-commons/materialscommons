@props([
    'datasets' => collect(),
    'listedInDatasets' => collect(),
])

@php
    $datasets = collect($datasets);
    $listedInDatasets = collect($listedInDatasets);

    $tagRowsFor = function ($sourceDatasets, $source) {
        return collect($sourceDatasets)
            ->flatMap(function ($dataset) use ($source) {
                return collect($dataset->tags ?? collect())
                    ->pluck('name')
                    ->filter()
                    ->unique()
                    ->map(fn($tag) => [
                        'tag' => $tag,
                        'tag_key' => mb_strtolower($tag),
                        'dataset_id' => $dataset->id,
                        'dataset_name' => $dataset->name,
                        'dataset_url' => filled($dataset->published_at ?? null)
                            ? route('public.datasets.show', $dataset)
                            : null,
                        'project_name' => $dataset->project?->name,
                        'owner_name' => $dataset->owner?->name,
                        'published_at' => $dataset->published_at,
                        'views_count' => $dataset->views_count ?? 0,
                        'downloads_count' => $dataset->downloads_count ?? 0,
                        'source' => $source,
                    ]);
            });
    };

    $myTagRows = $tagRowsFor($datasets, 'mine');
    $listedTagRows = $tagRowsFor($listedInDatasets, 'listed');

    $buildTagStats = function ($rows) {
        return collect($rows)
            ->groupBy('tag_key')
            ->map(function ($items) {
                $first = $items->first();

                return [
                    'tag' => $first['tag'],
                    'count' => $items->pluck('dataset_id')->unique()->count(),
                    'published_count' => $items->filter(fn($item) => filled($item['published_at']))->pluck('dataset_id')->unique()->count(),
                    'views_count' => $items->sum('views_count'),
                    'downloads_count' => $items->sum('downloads_count'),
                    'datasets' => $items->unique('dataset_id')->values(),
                ];
            })
            ->sortByDesc('count')
            ->values();
    };

    $myTags = $buildTagStats($myTagRows);
    $listedTags = $buildTagStats($listedTagRows);

    $allTags = $myTags
        ->concat($listedTags)
        ->groupBy(fn($item) => mb_strtolower($item['tag']))
        ->map(function ($items) {
            $first = $items->first();

            return [
                'tag' => $first['tag'],
                'count' => $items->sum('count'),
                'published_count' => $items->sum('published_count'),
                'views_count' => $items->sum('views_count'),
                'downloads_count' => $items->sum('downloads_count'),
            ];
        })
        ->sortByDesc('count')
        ->values();

    $topTags = $allTags->take(15)->values();
    $listedTagKeys = $listedTags->pluck('tag')->map(fn($name) => mb_strtolower($name));
    $myOnlyTags = $myTags->filter(fn($tag) => !$listedTagKeys->contains(mb_strtolower($tag['tag'])))->values();
    $sharedTags = $myTags->filter(fn($tag) => $listedTagKeys->contains(mb_strtolower($tag['tag'])))->values();

    $taggedMyDatasetCount = $datasets->filter(fn($dataset) => collect($dataset->tags ?? collect())->isNotEmpty())->count();
    $untaggedMyDatasetCount = $datasets->count() - $taggedMyDatasetCount;
    $listedTaggedDatasetCount = $listedInDatasets->filter(fn($dataset) => collect($dataset->tags ?? collect())->isNotEmpty())->count();
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.tags.summary-card
            label="Unique Tags"
            :value="$allTags->count()"
            hint="across all datasets"
            color="success"
            icon="fas fa-tags"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.tags.summary-card
            label="Tags In Mine"
            :value="$myTags->count()"
            hint="unique tags"
            color="primary"
            icon="fas fa-database"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.tags.summary-card
            label="Tags Listed In"
            :value="$listedTags->count()"
            hint="unique tags"
            color="info"
            icon="fas fa-list"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.tags.summary-card
            label="Shared Tags"
            :value="$sharedTags->count()"
            hint="mine + listed"
            color="warning"
            icon="fas fa-link"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.tags.summary-card
            label="Tagged Datasets"
            :value="$taggedMyDatasetCount + $listedTaggedDatasetCount"
            hint="with at least one tag"
            color="secondary"
            icon="fas fa-check-circle"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.tags.summary-card
            label="Needs Tags"
            :value="$untaggedMyDatasetCount"
            hint="my datasets"
            color="danger"
            icon="fas fa-exclamation-triangle"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-7">
        <x-dashboard.my-research.tags.charts.top-tags :tags="$topTags"/>
    </div>

    <div class="col-12 col-xl-5">
        <x-dashboard.my-research.tags.analytics
            :all-tags="$allTags"
            :my-tags="$myTags"
            :listed-tags="$listedTags"
            :shared-tags="$sharedTags"
            :my-only-tags="$myOnlyTags"
            :tagged-my-dataset-count="$taggedMyDatasetCount"
            :untagged-my-dataset-count="$untaggedMyDatasetCount"
            :listed-tagged-dataset-count="$listedTaggedDatasetCount"
            :datasets-count="$datasets->count()"
            :listed-in-datasets-count="$listedInDatasets->count()"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-dashboard.my-research.tags.tag-cloud
            title="Tags in my datasets"
            icon="fas fa-database"
            color="success"
            :tags="$myTags"
            empty-message="No tags found in your datasets."
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-dashboard.my-research.tags.tag-cloud
            title="Tags in datasets I'm listed in"
            icon="fas fa-list"
            color="info"
            :tags="$listedTags"
            empty-message="No tags found in datasets where you are listed."
        />
    </div>
</div>

<x-dashboard.my-research.tags.table
    :my-tags="$myTags"
    :listed-tags="$listedTags"
/>
