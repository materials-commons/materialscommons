<?php

namespace App\Actions\Projects\ResearchOverview;

use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BuildFilesTabMetricsAction
{
    public function execute(Project $project): array
    {
        return Cache::remember(
            "project:{$project->id}:research-overview:files:v1",
            now()->addMinutes(5),
            fn() => $this->build($project)
        );
    }

    private function build(Project $project): array
    {
        $rootDirectory = $project->rootDir
            ?? File::query()
                   ->active()
                   ->directories()
                   ->where('project_id', $project->id)
                   ->whereNull('dataset_id')
                   ->where('path', '/')
                   ->first();

        $rootDirectoryId = $rootDirectory?->id;

        $summary = File::query()
                       ->active()
                       ->where('project_id', $project->id)
                       ->whereNull('dataset_id')
                       ->selectRaw("
                           sum(case when mime_type <> 'directory' then 1 else 0 end) as files_count,
                           sum(case when mime_type = 'directory' and path <> '/' then 1 else 0 end) as folders_count,
                           coalesce(sum(case when mime_type <> 'directory' then size else 0 end), 0) as files_size,
                           sum(case when mime_type <> 'directory' and directory_id = ? then 1 else 0 end) as root_files_count,
                           count(distinct case
                               when mime_type <> 'directory'
                                and directory_id is not null
                                and directory_id <> ?
                               then directory_id
                           end) as folders_with_files_count
                       ", [$rootDirectoryId, $rootDirectoryId])
                       ->first();

        $filesCount = (int) ($summary->files_count ?? 0);
        $foldersCount = (int) ($summary->folders_count ?? 0);
        $filesSize = (int) ($summary->files_size ?? 0);
        $rootFilesCount = (int) ($summary->root_files_count ?? 0);
        $directoriesWithFilesCount = (int) ($summary->folders_with_files_count ?? 0);

        $filesPerFolder = $foldersCount > 0 ? round($filesCount / $foldersCount, 1) : $filesCount;
        $rootFilePercent = $filesCount > 0 ? min(100, max(0, round(($rootFilesCount / $filesCount) * 100))) : 0;
        $emptyFoldersEstimate = max(0, $foldersCount - $directoriesWithFilesCount);
        $averageFileSize = $filesCount > 0 ? (int) floor($filesSize / $filesCount) : 0;

        $distributions = $this->fileDistributions($project);
        $highlights = $this->fileHighlights($project);

        return [
            'rootDirectory'             => $rootDirectory,
            'rootDirectoryId'           => $rootDirectoryId,
            'filesCount'                => $filesCount,
            'foldersCount'              => $foldersCount,
            'filesSize'                 => $filesSize,
            'averageFileSize'           => $averageFileSize,
            'filesPerFolder'            => $filesPerFolder,
            'rootFilesCount'            => $rootFilesCount,
            'rootFilePercent'           => $rootFilePercent,
            'directoriesWithFilesCount' => $directoriesWithFilesCount,
            'emptyFoldersEstimate'      => $emptyFoldersEstimate,
            'extensionCounts'           => $distributions['extensionCounts'],
            'mimeTypeCounts'            => $distributions['mimeTypeCounts'],
            'recentFiles'               => $highlights['recentFiles'],
            'largestFiles'              => $highlights['largestFiles'],
        ];
    }

    private function fileDistributions(Project $project): array
    {
        $rows = DB::query()
                  ->fromSub(function ($query) use ($project) {
                      $query->from('files')
                            ->selectRaw("
                                'extension' as metric_type,
                                lower(
                                    case
                                        when name not like '%.%' then 'No extension'
                                        else substring_index(name, '.', -1)
                                    end
                                ) as metric_key,
                                count(*) as files_count
                            ")
                            ->where('project_id', $project->id)
                            ->whereNull('dataset_id')
                            ->whereNull('deleted_at')
                            ->where('current', true)
                            ->where('mime_type', '<>', 'directory')
                            ->groupBy('metric_key')
                            ->unionAll(
                                DB::table('files')
                                  ->selectRaw("
                                      'mime_type' as metric_type,
                                      coalesce(nullif(mime_type, ''), 'Unknown MIME type') as metric_key,
                                      count(*) as files_count
                                  ")
                                  ->where('project_id', $project->id)
                                  ->whereNull('dataset_id')
                                  ->whereNull('deleted_at')
                                  ->where('current', true)
                                  ->where('mime_type', '<>', 'directory')
                                  ->groupBy('metric_key')
                            );
                  }, 'file_distribution_metrics')
                  ->orderBy('metric_type')
                  ->orderByDesc('files_count')
                  ->get();

        return [
            'extensionCounts' => $rows
                ->where('metric_type', 'extension')
                ->take(12)
                ->map(fn($row) => (object) [
                    'extension'   => $row->metric_key,
                    'files_count' => (int) $row->files_count,
                ])
                ->values(),

            'mimeTypeCounts' => $rows
                ->where('metric_type', 'mime_type')
                ->take(8)
                ->map(fn($row) => (object) [
                    'mime_type'   => $row->metric_key,
                    'files_count' => (int) $row->files_count,
                ])
                ->values(),
        ];
    }

    private function fileHighlights(Project $project, int $limit = 8): array
    {
        $rows = DB::query()
                  ->fromSub(function ($query) use ($project) {
                      $query->from('files')
                            ->select([
                                'id',
                                'name',
                                'path',
                                'size',
                                'updated_at',
                                'mime_type',
                            ])
                            ->selectRaw('row_number() over (order by updated_at desc, id desc) as recent_rank')
                            ->selectRaw('row_number() over (order by case when size is null then 1 else 0 end, size desc, id desc) as size_rank')
                            ->where('project_id', $project->id)
                            ->whereNull('dataset_id')
                            ->whereNull('deleted_at')
                            ->where('current', true)
                            ->where('mime_type', '<>', 'directory');
                  }, 'ranked_files')
                  ->where(function ($query) use ($limit) {
                      $query->where('recent_rank', '<=', $limit)
                            ->orWhere(function ($query) use ($limit) {
                                $query->whereNotNull('size')
                                      ->where('size_rank', '<=', $limit);
                            });
                  })
                  ->get();

        return [
            'recentFiles' => $rows
                ->filter(fn($row) => (int) $row->recent_rank <= $limit)
                ->sortBy('recent_rank')
                ->values(),

            'largestFiles' => $rows
                ->filter(fn($row) => !is_null($row->size) && (int) $row->size_rank <= $limit)
                ->sortBy('size_rank')
                ->values(),
        ];
    }
}
