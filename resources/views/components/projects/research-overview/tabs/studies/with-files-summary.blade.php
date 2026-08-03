@props([
    'project',
])

@php
    $withFilesCount = \App\Models\Experiment::query()
        ->where('project_id', $project->id)
        ->whereHas('files')
        ->count();

    $totalStudies = (int) ($project->experiments_count ?? 0);

    $hint = $totalStudies > 0
        ? round(($withFilesCount / $totalStudies) * 100) . '% of studies'
        : 'no studies yet';
@endphp

<x-projects.research-overview.summary-card
    label="With Files"
    :value="$withFilesCount"
    :hint="$hint"
    color="primary"
/>
