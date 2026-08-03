<?php

namespace App\Actions\Projects\ResearchOverview;

use App\Models\Entity;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class BuildSamplesTabMetricsAction
{
    public function execute(Project $project): array
    {
        return Cache::remember(
            "project:{$project->id}:research-overview:samples:v1",
            now()->addMinutes(5),
            fn() => $this->build($project)
        );
    }

    private function build(Project $project): array
    {
        $samples = Entity::query()
                         ->where('project_id', $project->id)
                         ->where('category', 'experimental')
                         ->withCount([
                             'files',
                             'activities',
                             'experiments',
                             'datasets',
                             'entityStates',
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
                             'project_id',
                             'owner_id',
                             'created_at',
                             'updated_at',
                         ]);

        $totalSamples = $samples->count();

        $withFilesCount = $samples->where('files_count', '>', 0)->count();
        $withProcessesCount = $samples->where('activities_count', '>', 0)->count();
        $withStudiesCount = $samples->where('experiments_count', '>', 0)->count();
        $withDatasetsCount = $samples->where('datasets_count', '>', 0)->count();
        $withMeasurementsCount = $samples->where('entity_states_count', '>', 0)->count();
        $withWorkflowsCount = $samples->where('workflows_count', '>', 0)->count();
        $withTagsCount = $samples->where('tags_count', '>', 0)->count();

        $withDescriptionCount = $samples
            ->filter(fn($sample) => filled($sample->description ?? null) || filled($sample->summary ?? null))
            ->count();

        $samplesNeedingReview = $samples
            ->map(function ($sample) {
                $issues = collect();

                if ((int) ($sample->experiments_count ?? 0) === 0) {
                    $issues->push('No studies');
                }

                if ((int) ($sample->activities_count ?? 0) === 0) {
                    $issues->push('No processes');
                }

                if ((int) ($sample->entity_states_count ?? 0) === 0) {
                    $issues->push('No measurements');
                }

                if ((int) ($sample->files_count ?? 0) === 0) {
                    $issues->push('No files');
                }

                if (blank($sample->description ?? null) && blank($sample->summary ?? null)) {
                    $issues->push('No description');
                }

                if ((int) ($sample->tags_count ?? 0) === 0) {
                    $issues->push('No tags');
                }

                $sample->research_overview_issues = $issues;

                return $sample;
            })
            ->filter(fn($sample) => $sample->research_overview_issues->isNotEmpty())
            ->values();

        $categoryCounts = $samples
            ->map(fn($sample) => blank($sample->category ?? null) ? 'Unspecified' : $sample->category)
            ->countBy()
            ->sortDesc()
            ->map(fn($count, $category) => (object) [
                'category' => $category,
                'samples_count' => (int) $count,
            ])
            ->values();

        $coverageItems = collect([
            [
                'label' => 'Studies',
                'value' => $withStudiesCount,
                'color' => 'info',
                'hint' => 'samples linked to studies',
            ],
            [
                'label' => 'Processes',
                'value' => $withProcessesCount,
                'color' => 'secondary',
                'hint' => 'samples connected to processes',
            ],
            [
                'label' => 'Files',
                'value' => $withFilesCount,
                'color' => 'primary',
                'hint' => 'samples with files',
            ],
            [
                'label' => 'Datasets',
                'value' => $withDatasetsCount,
                'color' => 'success',
                'hint' => 'samples included in datasets',
            ],
            [
                'label' => 'Measurements',
                'value' => $withMeasurementsCount,
                'color' => 'warning',
                'hint' => 'samples with entity states/measurements',
            ],
        ]);

        $metadataChecks = collect([
            [
                'label' => 'Measurements',
                'value' => $withMeasurementsCount,
                'color' => 'warning',
                'hint' => 'samples with entity states',
            ],
            [
                'label' => 'Descriptions',
                'value' => $withDescriptionCount,
                'color' => 'primary',
                'hint' => 'samples with description or summary',
            ],
            [
                'label' => 'Tags',
                'value' => $withTagsCount,
                'color' => 'success',
                'hint' => 'samples with tags',
            ],
            [
                'label' => 'Processes',
                'value' => $withProcessesCount,
                'color' => 'secondary',
                'hint' => 'samples connected to process context',
            ],
        ]);

        $denominator = max(1, $totalSamples);

        $coveragePercent = $totalSamples > 0
            ? round($coverageItems->avg(fn($item) => ((int) $item['value'] / $denominator) * 100))
            : 0;

        $metadataPercent = $totalSamples > 0
            ? round($metadataChecks->avg(fn($item) => ((int) $item['value'] / $denominator) * 100))
            : 0;

        $readinessPercent = $totalSamples > 0
            ? round(($coveragePercent + $metadataPercent) / 2)
            : 0;

        return [
            'samples' => $samples,
            'recentSamples' => $samples->take(8)->values(),
            'samplesNeedingReview' => $samplesNeedingReview,
            'totalSamples' => $totalSamples,
            'withFilesCount' => $withFilesCount,
            'withProcessesCount' => $withProcessesCount,
            'withStudiesCount' => $withStudiesCount,
            'withDatasetsCount' => $withDatasetsCount,
            'withMeasurementsCount' => $withMeasurementsCount,
            'withWorkflowsCount' => $withWorkflowsCount,
            'withTagsCount' => $withTagsCount,
            'withDescriptionCount' => $withDescriptionCount,
            'totalFiles' => $samples->sum(fn($sample) => (int) ($sample->files_count ?? 0)),
            'totalProcesses' => $samples->sum(fn($sample) => (int) ($sample->activities_count ?? 0)),
            'totalStudies' => $samples->sum(fn($sample) => (int) ($sample->experiments_count ?? 0)),
            'totalDatasets' => $samples->sum(fn($sample) => (int) ($sample->datasets_count ?? 0)),
            'totalMeasurements' => $samples->sum(fn($sample) => (int) ($sample->entity_states_count ?? 0)),
            'totalWorkflows' => $samples->sum(fn($sample) => (int) ($sample->workflows_count ?? 0)),
            'categoryCounts' => $categoryCounts,
            'coverageItems' => $coverageItems,
            'metadataChecks' => $metadataChecks,
            'coveragePercent' => $coveragePercent,
            'metadataPercent' => $metadataPercent,
            'readinessPercent' => $readinessPercent,
        ];
    }
}
