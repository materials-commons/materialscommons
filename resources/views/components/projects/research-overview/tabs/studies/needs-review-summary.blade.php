@props([
    'project',
])

@php
    $needsReviewCount = \App\Models\Experiment::query()
        ->where('project_id', $project->id)
        ->withCount(['files', 'entities', 'activities'])
        ->get()
        ->filter(function ($study) {
            return (int) ($study->files_count ?? 0) === 0
                || (int) ($study->entities_count ?? 0) === 0
                || (int) ($study->activities_count ?? 0) === 0
                || blank($study->description ?? null);
        })
        ->count();

    $totalStudies = (int) ($project->experiments_count ?? 0);

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
