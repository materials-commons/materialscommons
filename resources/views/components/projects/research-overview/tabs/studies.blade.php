@props([
    'project',
    'metrics' => [],
])

@php
    $totalStudies = (int) ($metrics['totalStudies'] ?? 0);
    $withFilesCount = (int) ($metrics['withFilesCount'] ?? 0);
    $withSamplesCount = (int) ($metrics['withSamplesCount'] ?? 0);
    $needsReviewCount = collect($metrics['studiesNeedingReview'] ?? [])->count();

    $withFilesHint = $totalStudies > 0
        ? round(($withFilesCount / max(1, $totalStudies)) * 100) . '% of studies'
        : 'no studies yet';

    $withSamplesHint = $totalStudies > 0
        ? round(($withSamplesCount / max(1, $totalStudies)) * 100) . '% of studies'
        : 'no studies yet';
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.studies.summary-card
            label="Studies"
            :value="$totalStudies"
            hint="experiments"
            color="info"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.studies.summary-card
            label="With Files"
            :value="$withFilesCount"
            :hint="$withFilesHint"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.studies.summary-card
            label="With Samples"
            :value="$withSamplesCount"
            :hint="$withSamplesHint"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.studies.summary-card
            label="Needs Review"
            :value="$needsReviewCount"
            hint="missing key context"
            color="{{ $needsReviewCount > 0 ? 'warning' : 'success' }}"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
        <x-projects.research-overview.tabs.studies.overview
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-4">
        <x-projects.research-overview.tabs.studies.actions :project="$project"/>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.studies.coverage
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.studies.needs-review
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12">
        <x-projects.research-overview.tabs.studies.recent-studies
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>
