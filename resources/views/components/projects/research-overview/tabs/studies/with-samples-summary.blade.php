@props([
    'project',
])

@php
    $withSamplesCount = \App\Models\Experiment::query()
        ->where('project_id', $project->id)
        ->whereHas('entities')
        ->count();

    $totalStudies = (int) ($project->experiments_count ?? 0);

    $hint = $totalStudies > 0
        ? round(($withSamplesCount / $totalStudies) * 100) . '% of studies'
        : 'no studies yet';
@endphp

<x-projects.research-overview.summary-card
    label="With Samples"
    :value="$withSamplesCount"
    :hint="$hint"
    color="success"
/>
