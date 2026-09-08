<?php

namespace App\Actions\Projects\ResearchOverview;

use App\Models\Activity;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class BuildProcessesTabMetricsAction
{
    public function execute(Project $project): array
    {
        return Cache::remember(
            "project:{$project->id}:research-overview:processes:v1",
            now()->addMinutes(5),
            fn() => $this->build($project)
        );
    }

    private function build(Project $project): array
    {
        $processes = Activity::query()
                             ->where('project_id', $project->id)
                             ->where('name', '<>', 'Create Samples')
                             ->withCount([
                                 'files',
                                 'entities',
                                 'entityStates',
                                 'experiments',
                                 'datasets',
                                 'attributes',
                                 'workflows',
                                 'tags',
                             ])
                             ->orderByDesc('updated_at')
                             ->get([
                                 'id',
                                 'name',
                                 'description',
                                 'summary',
                                 'category',
                                 'atype',
                                 'project_id',
                                 'owner_id',
                                 'created_at',
                                 'updated_at',
                             ]);

        $totalProcesses = $processes->count();

        $withFilesCount = $processes->where('files_count', '>', 0)->count();
        $withSamplesCount = $processes->where('entities_count', '>', 0)->count();
        $withMeasurementsCount = $processes->where('entity_states_count', '>', 0)->count();
        $withStudiesCount = $processes->where('experiments_count', '>', 0)->count();
        $withDatasetsCount = $processes->where('datasets_count', '>', 0)->count();
        $withAttributesCount = $processes->where('attributes_count', '>', 0)->count();
        $withWorkflowsCount = $processes->where('workflows_count', '>', 0)->count();
        $withTagsCount = $processes->where('tags_count', '>', 0)->count();

        $withDescriptionCount = $processes
            ->filter(fn($process) => filled($process->description ?? null) || filled($process->summary ?? null))
            ->count();

        $processesNeedingReview = $processes
            ->map(function ($process) {
                $issues = collect();

                if ((int) ($process->entities_count ?? 0) === 0) {
                    $issues->push('No samples');
                }

                if ((int) ($process->experiments_count ?? 0) === 0) {
                    $issues->push('No studies');
                }

                if ((int) ($process->attributes_count ?? 0) === 0) {
                    $issues->push('No attributes');
                }

                if ((int) ($process->files_count ?? 0) === 0) {
                    $issues->push('No files');
                }

                if (blank($process->description ?? null) && blank($process->summary ?? null)) {
                    $issues->push('No description');
                }

                if (blank($process->category ?? null) && blank($process->atype ?? null)) {
                    $issues->push('No type');
                }

                $process->research_overview_issues = $issues;

                return $process;
            })
            ->filter(fn($process) => $process->research_overview_issues->isNotEmpty())
            ->values();

        $typeCounts = $processes
            ->map(fn($process) => blank($process->atype ?? null) ? 'Unspecified Type' : $process->atype)
            ->countBy()
            ->sortDesc()
            ->map(fn($count, $type) => (object) [
                'type' => $type,
                'processes_count' => (int) $count,
            ])
            ->values();

        $categoryCounts = $processes
            ->map(fn($process) => blank($process->category ?? null) ? 'Experimental / Unspecified' : $process->category)
            ->countBy()
            ->sortDesc()
            ->map(fn($count, $category) => (object) [
                'category' => $category,
                'processes_count' => (int) $count,
            ])
            ->values();

        $coverageItems = collect([
            [
                'label' => 'Samples',
                'value' => $withSamplesCount,
                'color' => 'success',
                'hint' => 'processes connected to samples/entities',
            ],
            [
                'label' => 'Studies',
                'value' => $withStudiesCount,
                'color' => 'info',
                'hint' => 'processes linked to studies',
            ],
            [
                'label' => 'Files',
                'value' => $withFilesCount,
                'color' => 'primary',
                'hint' => 'processes with files',
            ],
            [
                'label' => 'Datasets',
                'value' => $withDatasetsCount,
                'color' => 'secondary',
                'hint' => 'processes included in datasets',
            ],
            [
                'label' => 'Measurements',
                'value' => $withMeasurementsCount,
                'color' => 'warning',
                'hint' => 'processes connected to entity states/measurements',
            ],
        ]);

        $metadataChecks = collect([
            [
                'label' => 'Attributes',
                'value' => $withAttributesCount,
                'color' => 'secondary',
                'hint' => 'processes with metadata attributes',
            ],
            [
                'label' => 'Descriptions',
                'value' => $withDescriptionCount,
                'color' => 'primary',
                'hint' => 'processes with description or summary',
            ],
            [
                'label' => 'Types',
                'value' => $processes
                    ->filter(fn($process) => filled($process->atype ?? null) || filled($process->category ?? null))
                    ->count(),
                'color' => 'info',
                'hint' => 'processes with type or category',
            ],
            [
                'label' => 'Tags',
                'value' => $withTagsCount,
                'color' => 'success',
                'hint' => 'processes with tags',
            ],
        ]);

        $denominator = max(1, $totalProcesses);

        $coveragePercent = $totalProcesses > 0
            ? round($coverageItems->avg(fn($item) => ((int) $item['value'] / $denominator) * 100))
            : 0;

        $metadataPercent = $totalProcesses > 0
            ? round($metadataChecks->avg(fn($item) => ((int) $item['value'] / $denominator) * 100))
            : 0;

        $readinessPercent = $totalProcesses > 0
            ? round(($coveragePercent + $metadataPercent) / 2)
            : 0;

        return [
            'processes' => $processes,
            'recentProcesses' => $processes->take(8)->values(),
            'processesNeedingReview' => $processesNeedingReview,
            'totalProcesses' => $totalProcesses,
            'withFilesCount' => $withFilesCount,
            'withSamplesCount' => $withSamplesCount,
            'withMeasurementsCount' => $withMeasurementsCount,
            'withStudiesCount' => $withStudiesCount,
            'withDatasetsCount' => $withDatasetsCount,
            'withAttributesCount' => $withAttributesCount,
            'withWorkflowsCount' => $withWorkflowsCount,
            'withTagsCount' => $withTagsCount,
            'withDescriptionCount' => $withDescriptionCount,
            'totalFiles' => $processes->sum(fn($process) => (int) ($process->files_count ?? 0)),
            'totalSamples' => $processes->sum(fn($process) => (int) ($process->entities_count ?? 0)),
            'totalMeasurements' => $processes->sum(fn($process) => (int) ($process->entity_states_count ?? 0)),
            'totalStudies' => $processes->sum(fn($process) => (int) ($process->experiments_count ?? 0)),
            'totalDatasets' => $processes->sum(fn($process) => (int) ($process->datasets_count ?? 0)),
            'totalAttributes' => $processes->sum(fn($process) => (int) ($process->attributes_count ?? 0)),
            'typeCounts' => $typeCounts,
            'categoryCounts' => $categoryCounts,
            'coverageItems' => $coverageItems,
            'metadataChecks' => $metadataChecks,
            'coveragePercent' => $coveragePercent,
            'metadataPercent' => $metadataPercent,
            'readinessPercent' => $readinessPercent,
        ];
    }
}
