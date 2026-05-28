<?php

namespace App\Actions\BrowseTree\Builders;

use App\Actions\BrowseTree\Contracts\BrowseTreeNodeBuilder;
use App\Actions\BrowseTree\Support\BrowseTreeDates;
use App\Actions\BrowseTree\Support\BrowseTreeNode;
use App\DTO\BrowseTree\BrowseTreeContext;
use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FilesNodeBuilder implements BrowseTreeNodeBuilder
{
    private const int FILE_DISPLAY_LIMIT = 250;

    public function key(string $projectKey): string
    {
        return "{$projectKey}-files";
    }

    public function type(): string
    {
        return 'file';
    }

    public function title(): string
    {
        return 'Files';
    }

    public function icon(): string
    {
        return 'fas fa-file-alt text-secondary';
    }

    public function count(Project $project, BrowseTreeContext $context): int
    {
        return File::query()
                   ->active()
                   ->files()
                   ->where('project_id', $project->id)
                   ->whereNull('dataset_id')
                   ->count();
    }

    public function children(Project $project, string $projectKey, BrowseTreeContext $context): array
    {
        return $this->fileRootChildren($project, $this->key($projectKey), $context);
    }

    private function fileRootChildren(Project $project, string $parentKey, BrowseTreeContext $context): array
    {
        $rootDirectory = File::query()
                             ->active()
                             ->directories()
                             ->where('project_id', $project->id)
                             ->whereNull('dataset_id')
                             ->where('path', '/')
                             ->first();

        $rootDirectoryId = $rootDirectory?->id;
        $rootKey = "{$parentKey}-root";

        $childDirectories = File::query()
                                ->active()
                                ->directories()
                                ->where('project_id', $project->id)
                                ->whereNull('dataset_id')
                                ->when(
                                    $rootDirectoryId !== null,
                                    fn($query) => $query->where('directory_id', $rootDirectoryId),
                                    fn($query) => $query->whereNull('directory_id')
                                )
                                ->where('path', '<>', '/')
                                ->orderBy('name')
                                ->limit(self::FILE_DISPLAY_LIMIT)
                                ->get();

        $fileCount = $this->fileCountForDirectory($project, $rootDirectoryId);
        $childDirectoryFileCounts = $this->fileCountsForDirectories($project, $childDirectories);

        $children = $childDirectories
            ->map(fn(File $directory) => $this->directoryNode(
                directory: $directory,
                project: $project,
                key: "{$parentKey}-dir-{$directory->id}",
                context: $context,
                directFileCount: $childDirectoryFileCounts[$directory->id] ?? 0,
            ))
            ->all();

        return [
            ...$children,
            ...$this->fileVisibilityNodes(
                project: $project,
                directoryId: $rootDirectoryId,
                directoryKey: $rootKey,
                directoryLabel: 'project root',
                fileCount: $fileCount,
                context: $context,
                files: fn() => $this->fileLeavesForDirectory($project, $rootDirectoryId),
            ),
        ];
    }

    private function directoryNode(
        File $directory,
        Project $project,
        string $key,
        BrowseTreeContext $context,
        ?int $directFileCount = null,
    ): array {
        $isExpanded = $context->isExpanded($key);

        return BrowseTreeNode::folder(
            key: $key,
            title: $directory->name === '' ? '/' : $directory->name,
            icon: 'fas fa-folder text-warning',
            count: $directFileCount ?? $this->fileCountForDirectory($project, $directory->id),
            lazy: !$isExpanded,
            children: $isExpanded
                ? $this->directoryChildren($directory, $project, $key, $context)
                : [],
            searchTerms: [
                $directory->name,
                $directory->path,
            ],
        );
    }

    private function directoryChildren(
        File $directory,
        Project $project,
        string $parentKey,
        BrowseTreeContext $context,
    ): array {
        $childDirectories = File::query()
                                ->active()
                                ->directories()
                                ->where('project_id', $project->id)
                                ->whereNull('dataset_id')
                                ->where('directory_id', $directory->id)
                                ->orderBy('name')
                                ->limit(self::FILE_DISPLAY_LIMIT)
                                ->get();

        $fileCount = $this->fileCountForDirectory($project, $directory->id);
        $childDirectoryFileCounts = $this->fileCountsForDirectories($project, $childDirectories);

        $children = $childDirectories
            ->map(fn(File $childDirectory) => $this->directoryNode(
                directory: $childDirectory,
                project: $project,
                key: "{$parentKey}-dir-{$childDirectory->id}",
                context: $context,
                directFileCount: $childDirectoryFileCounts[$childDirectory->id] ?? 0,
            ))
            ->all();

        return [
            ...$children,
            ...$this->fileVisibilityNodes(
                project: $project,
                directoryId: $directory->id,
                directoryKey: $parentKey,
                directoryLabel: $directory->name === '' ? '/' : $directory->name,
                fileCount: $fileCount,
                context: $context,
                files: fn() => $this->fileLeavesForDirectory($project, $directory->id),
            ),
        ];
    }

    private function fileVisibilityNodes(
        Project $project,
        ?int $directoryId,
        string $directoryKey,
        string $directoryLabel,
        int $fileCount,
        BrowseTreeContext $context,
        callable $files,
    ): array {
        if ($fileCount === 0) {
            return [];
        }

        if (!$context->shouldShowFilesForDirectory($directoryKey)) {
            return [
                BrowseTreeNode::action(
                    key: "{$directoryKey}-show-files",
                    title: "View files in {$directoryLabel} ({$fileCount})",
                    icon: 'fas fa-eye text-muted',
                    directoryKey: $directoryKey,
                ),
            ];
        }

        $nodes = [
            BrowseTreeNode::action(
                key: "{$directoryKey}-hide-files",
                title: "Hide files in {$directoryLabel}",
                icon: 'fas fa-eye-slash text-muted',
                directoryKey: $directoryKey,
            ),
            ...$files(),
        ];

        if ($fileCount > self::FILE_DISPLAY_LIMIT) {
            $nodes[] = BrowseTreeNode::message(
                key: "{$directoryKey}-file-limit-message",
                title: 'Showing '.self::FILE_DISPLAY_LIMIT." of {$fileCount} files. Use search or organize this folder into subfolders for easier browsing.",
            );
        }

        return $nodes;
    }

    private function fileLeavesForDirectory(Project $project, ?int $directoryId): array
    {
        return File::query()
                   ->active()
                   ->files()
                   ->where('project_id', $project->id)
                   ->whereNull('dataset_id')
                   ->when(
                       $directoryId !== null,
                       fn($query) => $query->where('directory_id', $directoryId),
                       fn($query) => $query->whereNull('directory_id')
                   )
                   ->orderBy('name')
                   ->limit(self::FILE_DISPLAY_LIMIT)
                   ->get()
                   ->map(fn(File $file) => $this->fileLeaf($file, $project))
                   ->all();
    }

    private function fileCountForDirectory(Project $project, ?int $directoryId): int
    {
        return File::query()
                   ->active()
                   ->files()
                   ->where('project_id', $project->id)
                   ->whereNull('dataset_id')
                   ->when(
                       $directoryId !== null,
                       fn($query) => $query->where('directory_id', $directoryId),
                       fn($query) => $query->whereNull('directory_id')
                   )
                   ->count();
    }

    private function fileCountsForDirectories(Project $project, Collection $directories): array
    {
        $directoryIds = $directories->pluck('id')->filter()->values();

        if ($directoryIds->isEmpty()) {
            return [];
        }

        return File::query()
                   ->active()
                   ->files()
                   ->where('project_id', $project->id)
                   ->whereNull('dataset_id')
                   ->whereIn('directory_id', $directoryIds)
                   ->select('directory_id', DB::raw('count(*) as files_count'))
                   ->groupBy('directory_id')
                   ->pluck('files_count', 'directory_id')
                   ->mapWithKeys(fn($count, $directoryId) => [(int) $directoryId => (int) $count])
                   ->all();
    }

    private function fileLeaf(File $file, Project $project): array
    {
        return BrowseTreeNode::leaf(
            key: "file-{$file->id}",
            type: 'file',
            title: $file->name,
            icon: $this->fileIcon($file),
            badge: 'File',
            project: $project->name,
            location: "{$project->name} > Files > {$file->path}",
            description: $file->description ?? $file->summary ?? '',
            tags: [],
            experiment: null,
            dateBucket: BrowseTreeDates::bucket($file->updated_at),
            dateLabel: BrowseTreeDates::label($file->updated_at),
            url: route('projects.files.show', [$project, $file]),
            searchTerms: [
                $file->name,
                $file->description ?? '',
                $file->summary ?? '',
                $file->path,
                $file->mime_type,
            ],
        );
    }

    private function fileIcon(File $file): string
    {
        if (Str::contains($file->mime_type ?? '', 'image')) {
            return 'fas fa-file-image text-secondary';
        }

        if (Str::contains($file->mime_type ?? '', 'spreadsheet') || Str::endsWith($file->name, ['.csv', '.xlsx'])) {
            return 'fas fa-file-csv text-secondary';
        }

        if (Str::endsWith($file->name, ['.pdf'])) {
            return 'fas fa-file-pdf text-secondary';
        }

        return 'fas fa-file-alt text-secondary';
    }
}
