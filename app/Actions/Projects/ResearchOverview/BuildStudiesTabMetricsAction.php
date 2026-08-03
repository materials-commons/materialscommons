<?php

namespace App\Actions\Projects\ResearchOverview;

use App\Models\Experiment;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class BuildStudiesTabMetricsAction
{
    public function execute(Project $project): array
    {
        return Cache::remember(
            "project:{$project->id}:research-overview:studies:v1",
            now()->addMinutes(5),
            fn() => $this->build($project)
        );
    }

    private function build(Project $project): array
    {
        $studies = Experiment::query()
                             ->where('project_id', $project->id)
                             ->withCount(['files', 'entities', 'activities', 'datasets'])
                             ->orderByDesc('updated_at')
                             ->get([
                                 'id',
                                 'name',
                                 'description',
                                 'summary',
                                 'project_id',
                                 'updated_at',
                                 'created_at',
                             ]);

        $totalStudies = $studies->count();

        $withFilesCount = $studies->where('files_count', '>', 0)->count();
        $withSamplesCount = $studies->where('entities_count', '>', 0)->count();
        $withActivitiesCount = $studies->where('activities_count', '>', 0)->count();
        $withDatasetsCount = $studies->where('datasets_count', '>', 0)->count();

        $withDescriptionCount = $studies
            ->filter(fn($study) => filled($study->description ?? null) || filled($study->summary ?? null))
            ->count();

        $studiesNeedingReview = $studies
            ->map(function ($study) {
                $issues = collect();

                if ((int) ($study->files_count ?? 0) === 0) {
                    $issues->push('No files');
                }

                if ((int) ($study->entities_count ?? 0) === 0) {
                    $issues->push('No samples');
                }

                if ((int) ($study->activities_count ?? 0) === 0) {
                    $issues->push('No processes');
                }

                if ((int) ($study->datasets_count ?? 0) === 0) {
                    $issues->push('No dataset link');
                }

                if (blank($study->description ?? null) && blank($study->summary ?? null)) {
                    $issues->push('No description');
                }

                $study->research_overview_issues = $issues;

                return $study;
            })
            ->filter(fn($study) => $study->research_overview_issues->isNotEmpty())
            ->values();

        $coverageItems = collect([
            [
                'label' => 'Files',
                'value' => $withFilesCount,
                'color' => 'primary',
                'hint'  => 'studies with at least one file',
            ],
            [
                'label' => 'Samples',
                'value' => $withSamplesCount,
                'color' => 'success',
                'hint'  => 'studies with samples or computations',
            ],
            [
                'label' => 'Processes',
                'value' => $withActivitiesCount,
                'color' => 'secondary',
                'hint'  => 'studies with activities/processes',
            ],
            [
                'label' => 'Datasets',
                'value' => $withDatasetsCount,
                'color' => 'success',
                'hint'  => 'studies linked to datasets',
            ],
            [
                'label' => 'Descriptions',
                'value' => $withDescriptionCount,
                'color' => 'warning',
                'hint'  => 'studies with narrative context',
            ],
        ]);

        $coverageDenominator = max(1, $totalStudies);

        $overallCoverage = $totalStudies > 0
            ? round($coverageItems->avg(fn($item) => ($item['value'] / $coverageDenominator) * 100))
            : 0;

        $readinessChecks = collect([
            $totalStudies > 0,
            $withFilesCount > 0,
            $withSamplesCount > 0,
            $withActivitiesCount > 0,
            $withDescriptionCount > 0,
        ]);

        $readinessPercent = $readinessChecks->count() > 0
            ? round(($readinessChecks->filter()->count() / $readinessChecks->count()) * 100)
            : 0;

        return [
            'studies'              => $studies,
            'recentStudies'        => $studies->take(8)->values(),
            'studiesNeedingReview' => $studiesNeedingReview,
            'totalStudies'         => $totalStudies,
            'withFilesCount'       => $withFilesCount,
            'withSamplesCount'     => $withSamplesCount,
            'withActivitiesCount'  => $withActivitiesCount,
            'withDatasetsCount'    => $withDatasetsCount,
            'withDescriptionCount' => $withDescriptionCount,
            'coverageItems'        => $coverageItems,
            'overallCoverage'      => $overallCoverage,
            'readinessPercent'     => $readinessPercent,
        ];
    }
}
