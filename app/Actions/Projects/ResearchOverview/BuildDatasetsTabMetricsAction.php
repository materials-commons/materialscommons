<?php

namespace App\Actions\Projects\ResearchOverview;

use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BuildDatasetsTabMetricsAction
{
    public function execute(Project $project): array
    {
        return Cache::remember(
            "project:{$project->id}:research-overview:datasets:v1",
            now()->addMinutes(5),
            fn() => $this->build($project)
        );
    }

    private function build(Project $project): array
    {
        $datasets = Dataset::query()
                           ->where('project_id', $project->id)
                           ->with(['tags:id,name'])
                           ->withCount([
                               'files',
                               'entities',
                               'activities',
                               'experiments',
                               'workflows',
                               'communities',
                               'comments',
                               'views',
                               'downloads',
                               'papers',
                           ])
                           ->orderByDesc('updated_at')
                           ->get([
                               'id',
                               'uuid',
                               'name',
                               'description',
                               'summary',
                               'authors',
                               'ds_authors',
                               'license',
                               'license_link',
                               'doi',
                               'owner_id',
                               'project_id',
                               'published_at',
                               'privately_published_at',
                               'test_published_at',
                               'zipfile_size',
                               'globus_path_exists',
                               'file_selection',
                               'created_at',
                               'updated_at',
                           ]);

        $totalDatasets = $datasets->count();
        $publishedDatasets = $datasets->filter(fn($dataset) => filled($dataset->published_at));
        $draftDatasets = $datasets->filter(fn($dataset) => blank($dataset->published_at));
        $privateDatasets = $datasets->filter(fn($dataset) => filled($dataset->privately_published_at));
        $testPublishedDatasets = $datasets->filter(fn($dataset) => filled($dataset->test_published_at));

        $withFilesCount = $datasets->where('files_count', '>', 0)->count();
        $withSamplesCount = $datasets->where('entities_count', '>', 0)->count();
        $withProcessesCount = $datasets->where('activities_count', '>', 0)->count();
        $withStudiesCount = $datasets->where('experiments_count', '>', 0)->count();
        $withWorkflowsCount = $datasets->where('workflows_count', '>', 0)->count();
        $withCommunitiesCount = $datasets->where('communities_count', '>', 0)->count();
        $withPapersCount = $datasets->where('papers_count', '>', 0)->count();

        $withLicenseCount = $datasets->filter(fn($dataset) => filled($dataset->license))->count();
        $withDoiCount = $datasets->filter(fn($dataset) => filled($dataset->doi))->count();
        $withDescriptionCount = $datasets
            ->filter(fn($dataset) => filled($dataset->description) || filled($dataset->summary))
            ->count();
        $withAuthorsCount = $datasets
            ->filter(fn($dataset) => filled($dataset->authors) || filled($dataset->ds_authors))
            ->count();
        $withTagsCount = $datasets->filter(fn($dataset) => $dataset->tags->isNotEmpty())->count();
        $withSelectedFilesCount = $datasets->filter(fn($dataset) => $this->hasSelectedFiles($dataset))->count();

        $needsMetadata = $datasets
            ->map(function ($dataset) {
                $issues = collect();

                if (blank($dataset->description) && blank($dataset->summary)) {
                    $issues->push('No description');
                }

                if (blank($dataset->authors) && blank($dataset->ds_authors)) {
                    $issues->push('No authors');
                }

                if (blank($dataset->license)) {
                    $issues->push('No license');
                }

                if (blank($dataset->doi) && filled($dataset->published_at)) {
                    $issues->push('No DOI');
                }

                if ($dataset->tags->isEmpty()) {
                    $issues->push('No tags');
                }

                if ((int) ($dataset->files_count ?? 0) === 0 && !$this->hasSelectedFiles($dataset)) {
                    $issues->push('No files selected');
                }

                if ((int) ($dataset->experiments_count ?? 0) === 0) {
                    $issues->push('No studies');
                }

                $dataset->research_overview_issues = $issues;

                return $dataset;
            })
            ->filter(fn($dataset) => $dataset->research_overview_issues->isNotEmpty())
            ->values();

        $licenseCounts = $datasets
            ->map(fn($dataset) => blank($dataset->license) ? 'Missing License' : $dataset->license)
            ->countBy()
            ->sortDesc()
            ->map(fn($count, $license) => (object) [
                'license' => $license,
                'datasets_count' => (int) $count,
            ])
            ->values();

        $contentCounts = [
            'Files' => $datasets->sum(fn($dataset) => (int) ($dataset->files_count ?? 0)),
            'Samples' => $datasets->sum(fn($dataset) => (int) ($dataset->entities_count ?? 0)),
            'Processes' => $datasets->sum(fn($dataset) => (int) ($dataset->activities_count ?? 0)),
            'Studies' => $datasets->sum(fn($dataset) => (int) ($dataset->experiments_count ?? 0)),
            'Workflows' => $datasets->sum(fn($dataset) => (int) ($dataset->workflows_count ?? 0)),
        ];

        $coverageItems = collect([
            [
                'label' => 'Files',
                'value' => $withFilesCount,
                'color' => 'primary',
                'hint' => 'datasets with files attached',
            ],
            [
                'label' => 'Samples',
                'value' => $withSamplesCount,
                'color' => 'success',
                'hint' => 'datasets with samples/entities',
            ],
            [
                'label' => 'Processes',
                'value' => $withProcessesCount,
                'color' => 'secondary',
                'hint' => 'datasets with activities/processes',
            ],
            [
                'label' => 'Studies',
                'value' => $withStudiesCount,
                'color' => 'info',
                'hint' => 'datasets linked to studies',
            ],
            [
                'label' => 'Metadata',
                'value' => $totalDatasets - $needsMetadata->count(),
                'color' => 'warning',
                'hint' => 'datasets without obvious metadata issues',
            ],
        ]);

        $metadataChecks = collect([
            [
                'label' => 'Description',
                'value' => $withDescriptionCount,
                'color' => 'primary',
                'hint' => 'datasets with description or summary',
            ],
            [
                'label' => 'Authors',
                'value' => $withAuthorsCount,
                'color' => 'info',
                'hint' => 'datasets with authors',
            ],
            [
                'label' => 'License',
                'value' => $withLicenseCount,
                'color' => 'success',
                'hint' => 'datasets with license',
            ],
            [
                'label' => 'DOI',
                'value' => $withDoiCount,
                'color' => 'secondary',
                'hint' => 'datasets with DOI',
            ],
            [
                'label' => 'Tags',
                'value' => $withTagsCount,
                'color' => 'warning',
                'hint' => 'datasets with tags',
            ],
        ]);

        $denominator = max(1, $totalDatasets);

        $coveragePercent = $totalDatasets > 0
            ? round($coverageItems->avg(fn($item) => ((int) $item['value'] / $denominator) * 100))
            : 0;

        $metadataPercent = $totalDatasets > 0
            ? round($metadataChecks->avg(fn($item) => ((int) $item['value'] / $denominator) * 100))
            : 0;

        $publishedPercent = $totalDatasets > 0
            ? round(($publishedDatasets->count() / $totalDatasets) * 100)
            : 0;

        return [
            'datasets' => $datasets,
            'recentDatasets' => $datasets->take(8)->values(),
            'needsMetadata' => $needsMetadata,
            'publishedDatasets' => $publishedDatasets->values(),
            'draftDatasets' => $draftDatasets->values(),
            'privateDatasets' => $privateDatasets->values(),
            'testPublishedDatasets' => $testPublishedDatasets->values(),
            'totalDatasets' => $totalDatasets,
            'publishedCount' => $publishedDatasets->count(),
            'draftCount' => $draftDatasets->count(),
            'privateCount' => $privateDatasets->count(),
            'testPublishedCount' => $testPublishedDatasets->count(),
            'publishedPercent' => $publishedPercent,
            'withFilesCount' => $withFilesCount,
            'withSamplesCount' => $withSamplesCount,
            'withProcessesCount' => $withProcessesCount,
            'withStudiesCount' => $withStudiesCount,
            'withWorkflowsCount' => $withWorkflowsCount,
            'withCommunitiesCount' => $withCommunitiesCount,
            'withPapersCount' => $withPapersCount,
            'withSelectedFilesCount' => $withSelectedFilesCount,
            'withDescriptionCount' => $withDescriptionCount,
            'withAuthorsCount' => $withAuthorsCount,
            'withLicenseCount' => $withLicenseCount,
            'withDoiCount' => $withDoiCount,
            'withTagsCount' => $withTagsCount,
            'totalViews' => $datasets->sum(fn($dataset) => (int) ($dataset->views_count ?? 0)),
            'totalDownloads' => $datasets->sum(fn($dataset) => (int) ($dataset->downloads_count ?? 0)),
            'totalFiles' => $datasets->sum(fn($dataset) => (int) ($dataset->files_count ?? 0)),
            'totalSamples' => $datasets->sum(fn($dataset) => (int) ($dataset->entities_count ?? 0)),
            'totalProcesses' => $datasets->sum(fn($dataset) => (int) ($dataset->activities_count ?? 0)),
            'totalStudies' => $datasets->sum(fn($dataset) => (int) ($dataset->experiments_count ?? 0)),
            'totalZipfileSize' => $datasets->sum(fn($dataset) => (int) ($dataset->zipfile_size ?? 0)),
            'licenseCounts' => $licenseCounts,
            'contentCounts' => $contentCounts,
            'coverageItems' => $coverageItems,
            'metadataChecks' => $metadataChecks,
            'coveragePercent' => $coveragePercent,
            'metadataPercent' => $metadataPercent,
        ];
    }

    private function hasSelectedFiles(Dataset $dataset): bool
    {
        $fileSelection = $dataset->file_selection ?? [];

        return !empty($fileSelection['include_files'] ?? [])
            || !empty($fileSelection['include_dirs'] ?? []);
    }
}
