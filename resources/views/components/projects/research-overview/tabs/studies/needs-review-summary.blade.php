@props([
    'project',
    'metrics' => [],
])

@php
    $needsReviewCount = collect($metrics['studiesNeedingReview'] ?? [])->count();
    $totalStudies = (int) ($metrics['totalStudies'] ?? 0);

    $hint = $totalStudies > 0
        ? 'missing key context'
        : 'no studies yet';
@endphp

<x-projects.research-overview.summary-card
    label="Needs Review"
    :value="$needsReviewCount"
    :hint="$hint"
    color="{{ $needsReviewCount > 0 ? 'warning' : 'success' }}"
/>
