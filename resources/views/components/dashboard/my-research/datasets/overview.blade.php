@props([
    'datasets' => collect(),
    'projects' => collect(),
])

@php
    $datasets = collect($datasets);
    $projects = collect($projects);

    $publishedDatasets = $datasets->filter(fn($dataset) => filled($dataset->published_at ?? null));
    $draftDatasets = $datasets->reject(fn($dataset) => filled($dataset->published_at ?? null));
    $ownedDatasets = $datasets->filter(fn($dataset) => (int) ($dataset->owner_id ?? 0) === auth()->id());
    $authoredDatasets = $datasets->filter(function ($dataset) {
        return (int) ($dataset->owner_id ?? 0) !== auth()->id();
    });

    $missingLicense = $datasets->filter(fn($dataset) => blank($dataset->license ?? null));
    $missingDoi = $datasets->filter(fn($dataset) => blank($dataset->doi ?? null));
    $needsMetadata = $datasets->filter(function ($dataset) {
        return blank($dataset->license ?? null)
            || blank($dataset->doi ?? null)
            || blank($dataset->description ?? null)
            || blank($dataset->ds_authors ?? null)
            || blank($dataset->name ?? null);
    });

    $needsAttention = $datasets->filter(function ($dataset) {
        return blank($dataset->license ?? null)
            || blank($dataset->doi ?? null)
            || blank($dataset->description ?? null)
            || blank($dataset->ds_authors ?? null)
            || blank($dataset->published_at ?? null);
    });

    $publicEngagementDatasets = $publishedDatasets->filter(function ($dataset) {
        return (int) ($dataset->views_count ?? 0) > 0
            || (int) ($dataset->downloads_count ?? 0) > 0;
    });
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.datasets.summary-card
            label="Published"
            :value="$publishedDatasets->count()"
            hint="public datasets"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.datasets.summary-card
            label="Draft"
            :value="$draftDatasets->count()"
            hint="unpublished"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.datasets.summary-card
            label="Owned"
            :value="$ownedDatasets->count()"
            hint="you own"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.datasets.summary-card
            label="Author"
            :value="$authoredDatasets->count()"
            hint="listed author"
            color="info"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.datasets.summary-card
            label="Needs Metadata"
            :value="$needsMetadata->count()"
            hint="missing fields"
            color="danger"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.datasets.summary-card
            label="Engaged"
            :value="$publicEngagementDatasets->count()"
            hint="views/downloads"
            color="secondary"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-7">
        <x-dashboard.my-research.datasets.analytics
            :datasets="$datasets"
            :published-datasets="$publishedDatasets"
            :draft-datasets="$draftDatasets"
            :missing-license="$missingLicense"
            :missing-doi="$missingDoi"
            :needs-metadata="$needsMetadata"
            :public-engagement-datasets="$publicEngagementDatasets"
        />
    </div>

    <div class="col-12 col-xl-5">
        <x-dashboard.my-research.datasets.needs-attention
            :datasets="$needsAttention"
        />
    </div>
</div>

<x-dashboard.my-research.datasets.table
    :datasets="$datasets"
/>
