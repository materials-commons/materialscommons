@props([
    'project',
])

@php
    $unlinkedFilesCount = \App\Models\File::query()
        ->active()
        ->files()
        ->where('project_id', $project->id)
        ->whereNull('dataset_id')
        ->count();
@endphp

<x-projects.research-overview.summary-card
    label="Unlinked"
    :value="$unlinkedFilesCount"
    hint="not in datasets"
    color="{{ $unlinkedFilesCount > 0 ? 'warning' : 'success' }}"
/>
