<?php

namespace App\Actions\BrowseTree;

use App\Models\Activity;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Traits\Projects\UserProjects;
use Illuminate\Support\Str;

class BuildBrowseTreeAction
{
    use UserProjects;

    public function execute(?Project $project, User $user, string $scope, array $expandedNodeKeys = []): array
    {
        if ($scope === 'project' && $project !== null) {
            return [
                $this->projectNode($project, 'current-project', $expandedNodeKeys),
            ];
        }

        return $this->getUserProjects($user->id)
                    ->take(50)
                    ->map(fn(Project $userProject) => $this->projectNode(
                        $userProject,
                        "project-{$userProject->id}",
                        $expandedNodeKeys
                    ))
                    ->values()
                    ->all();
    }

    private function projectNode(Project $project, string $key, array $expandedNodeKeys): array
    {
        $isExpanded = in_array($key, $expandedNodeKeys, true);

        return [
            'key'         => $key,
            'projectKey'  => $key,
            'kind'        => 'folder',
            'type'        => 'folder',
            'title'       => $project->name,
            'icon'        => 'fas fa-folder text-warning',
            'count'       => null,
            'lazy'        => !$isExpanded,
            'searchTerms' => [
                $project->name,
                $project->description ?? '',
                $project->summary ?? '',
            ],
            'children'    => $isExpanded
                ? $this->projectCategoryNodes($project, $key, $expandedNodeKeys)
                : [],
        ];
    }

    private function projectCategoryNodes(Project $project, string $projectKey, array $expandedNodeKeys): array
    {
        return [
            $this->categoryNode(
                key: "{$projectKey}-samples",
                title: 'Samples',
                icon: 'fas fa-vials text-success',
                count: $this->samplesCount($project),
                children: fn() => $this->sampleLeaves($project),
                expandedNodeKeys: $expandedNodeKeys
            ),
            $this->categoryNode(
                key: "{$projectKey}-computations",
                title: 'Computations',
                icon: 'fas fa-cogs text-primary',
                count: $this->computationsCount($project),
                children: fn() => $this->computationLeaves($project),
                expandedNodeKeys: $expandedNodeKeys
            ),
            $this->categoryNode(
                key: "{$projectKey}-files",
                title: 'Files',
                icon: 'fas fa-file-alt text-secondary',
                count: $this->filesCount($project),
                children: fn() => $this->fileRootChildren($project, "{$projectKey}-files", $expandedNodeKeys),
                expandedNodeKeys: $expandedNodeKeys
            ),
            $this->categoryNode(
                key: "{$projectKey}-datasets",
                title: 'Datasets',
                icon: 'fas fa-database text-info',
                count: $this->datasetsCount($project),
                children: fn() => $this->datasetLeaves($project),
                expandedNodeKeys: $expandedNodeKeys
            ),
            $this->categoryNode(
                key: "{$projectKey}-experiments",
                title: 'Experiments',
                icon: 'fas fa-flask text-purple',
                count: $this->experimentsCount($project),
                children: fn() => $this->experimentNodes($project, "{$projectKey}-experiments", $expandedNodeKeys),
                expandedNodeKeys: $expandedNodeKeys
            ),
        ];
    }

    private function categoryNode(
        string $key,
        string $title,
        string $icon,
        int $count,
        callable $children,
        array $expandedNodeKeys
    ): array {
        $isExpanded = in_array($key, $expandedNodeKeys, true);

        return [
            'key'      => $key,
            'kind'     => 'folder',
            'type'     => 'folder',
            'title'    => $title,
            'icon'     => $icon,
            'count'    => $count,
            'lazy'     => !$isExpanded,
            'children' => $isExpanded ? $children() : [],
        ];
    }

    private function samplesCount(Project $project): int
    {
        return Entity::query()
                     ->where('project_id', $project->id)
                     ->whereNull('dataset_id')
                     ->where('category', 'experimental')
                     ->count();
    }

    private function computationsCount(Project $project): int
    {
        return Entity::query()
                     ->where('project_id', $project->id)
                     ->whereNull('dataset_id')
                     ->where('category', 'computational')
                     ->count();
    }

    private function filesCount(Project $project): int
    {
        return File::query()
                   ->active()
                   ->files()
                   ->where('project_id', $project->id)
                   ->whereNull('dataset_id')
                   ->count();
    }

    private function datasetsCount(Project $project): int
    {
        return Dataset::query()
                      ->where('project_id', $project->id)
                      ->count();
    }

    private function experimentsCount(Project $project): int
    {
        return Experiment::query()
                         ->where('project_id', $project->id)
                         ->count();
    }

    private function sampleLeaves(Project $project): array
    {
        return Entity::query()
                     ->with(['experiments'])
                     ->where('project_id', $project->id)
                     ->whereNull('dataset_id')
                     ->where('category', 'experimental')
                     ->orderBy('name')
                     ->limit(100)
                     ->get()
                     ->map(fn(Entity $entity) => $this->sampleLeaf($entity, $project))
                     ->all();
    }

    private function sampleLeaf(Entity $entity, Project $project): array
    {
        $experiment = $entity->experiments->first();

        return [
            'key'         => "sample-{$entity->id}",
            'kind'        => 'leaf',
            'type'        => 'sample',
            'title'       => $entity->name,
            'icon'        => 'fas fa-vial text-success',
            'badge'       => 'Sample',
            'project'     => $project->name,
            'location'    => $experiment === null
                ? "{$project->name} > Samples"
                : "{$project->name} > Experiments > {$experiment->name} > Samples",
            'description' => $entity->description ?? $entity->summary ?? '',
            'tags'        => $this->tagsFor($entity),
            'experiment'  => $experiment?->name,
            'dateBucket'  => $this->dateBucket($entity->updated_at),
            'dateLabel'   => $this->dateLabel($entity->updated_at),
            'url'         => route('projects.entities.show', [$project, $entity]),
            'searchTerms' => [
                $entity->name,
                $entity->description ?? '',
                $entity->summary ?? '',
                $experiment?->name ?? '',
            ],
        ];
    }

    private function computationLeaves(Project $project): array
    {
        return Entity::query()
                     ->with(['experiments'])
                     ->where('project_id', $project->id)
                     ->whereNull('dataset_id')
                     ->where('category', 'computational')
                     ->orderBy('name')
                     ->limit(100)
                     ->get()
                     ->map(fn(Entity $entity) => $this->computationLeaf($entity, $project))
                     ->all();
    }

    private function computationLeaf(Entity $entity, Project $project): array
    {
        $experiment = $entity->experiments->first();

        return [
            'key'         => "computation-{$entity->id}",
            'kind'        => 'leaf',
            'type'        => 'computation',
            'title'       => $entity->name,
            'icon'        => 'fas fa-microchip text-primary',
            'badge'       => 'Computation',
            'project'     => $project->name,
            'location'    => $experiment === null
                ? "{$project->name} > Computations"
                : "{$project->name} > Experiments > {$experiment->name} > Computations",
            'description' => $entity->description ?? $entity->summary ?? '',
            'tags'        => $this->tagsFor($entity),
            'experiment'  => $experiment?->name,
            'dateBucket'  => $this->dateBucket($entity->updated_at),
            'dateLabel'   => $this->dateLabel($entity->updated_at),
            'url'         => route('projects.computations.entities.show', [$project, $entity]),
            'searchTerms' => [
                $entity->name,
                $entity->description ?? '',
                $entity->summary ?? '',
                $experiment?->name ?? '',
            ],
        ];
    }

    private function datasetLeaves(Project $project): array
    {
        return Dataset::query()
                      ->with(['tags'])
                      ->where('project_id', $project->id)
                      ->orderByDesc('updated_at')
                      ->limit(100)
                      ->get()
                      ->map(fn(Dataset $dataset) => $this->datasetLeaf($dataset, $project))
                      ->all();
    }

    private function datasetLeaf(Dataset $dataset, Project $project): array
    {
        return [
            'key'         => "dataset-{$dataset->id}",
            'kind'        => 'leaf',
            'type'        => 'dataset',
            'title'       => $dataset->name,
            'icon'        => 'fas fa-database text-info',
            'badge'       => $dataset->isPublished() ? 'Published Dataset' : 'Dataset',
            'project'     => $project->name,
            'location'    => "{$project->name} > Datasets",
            'description' => $dataset->description ?? $dataset->summary ?? '',
            'tags'        => $this->tagsFor($dataset),
            'experiment'  => null,
            'dateBucket'  => $this->dateBucket($dataset->updated_at),
            'dateLabel'   => $this->dateLabel($dataset->updated_at),
            'url'         => route('projects.datasets.show', [$project, $dataset]),
            'searchTerms' => [
                $dataset->name,
                $dataset->description ?? '',
                $dataset->summary ?? '',
            ],
        ];
    }

    private function experimentNodes(Project $project, string $parentKey, array $expandedNodeKeys): array
    {
        return Experiment::query()
                         ->where('project_id', $project->id)
                         ->orderBy('name')
                         ->limit(100)
                         ->get()
                         ->map(fn(Experiment $experiment) => $this->experimentNode(
                             $experiment,
                             $project,
                             "{$parentKey}-experiment-{$experiment->id}",
                             $expandedNodeKeys
                         ))
                         ->all();
    }

    private function experimentNode(
        Experiment $experiment,
        Project $project,
        string $key,
        array $expandedNodeKeys
    ): array {
        $isExpanded = in_array($key, $expandedNodeKeys, true);

        return [
            'key'         => $key,
            'kind'        => 'folder',
            'type'        => 'folder',
            'title'       => $experiment->name,
            'icon'        => 'fas fa-flask text-purple',
            'count'       => null,
            'lazy'        => !$isExpanded,
            'searchTerms' => [
                $experiment->name,
                $experiment->description ?? '',
                $experiment->summary ?? '',
            ],
            'children'    => $isExpanded
                ? [
                    $this->experimentSamplesNode($experiment, $project, "{$key}-samples"),
                    $this->experimentComputationsNode($experiment, $project, "{$key}-computations"),
                ]
                : [],
        ];
    }

    private function experimentSamplesNode(Experiment $experiment, Project $project, string $key): array
    {
        $samples = $experiment->experimental_entities()
                              ->orderBy('name')
                              ->limit(100)
                              ->get();

        return [
            'key'      => $key,
            'kind'     => 'folder',
            'type'     => 'folder',
            'title'    => 'Samples',
            'icon'     => 'fas fa-vials text-success',
            'count'    => $samples->count(),
            'children' => $samples->map(fn(Entity $entity) => $this->sampleLeaf($entity, $project))->all(),
        ];
    }

    private function experimentComputationsNode(Experiment $experiment, Project $project, string $key): array
    {
        $computations = $experiment->computational_entities()
                                   ->orderBy('name')
                                   ->limit(100)
                                   ->get();

        return [
            'key'      => $key,
            'kind'     => 'folder',
            'type'     => 'folder',
            'title'    => 'Computations',
            'icon'     => 'fas fa-cogs text-primary',
            'count'    => $computations->count(),
            'children' => $computations->map(fn(Entity $entity) => $this->computationLeaf($entity, $project))->all(),
        ];
    }

    private function fileRootChildren(Project $project, string $parentKey, array $expandedNodeKeys): array
    {
        $rootDirectories = File::query()
                               ->active()
                               ->directories()
                               ->where('project_id', $project->id)
                               ->whereNull('dataset_id')
                               ->where(function ($query) {
                                   $query->whereNull('directory_id')
                                         ->orWhere('path', '/');
                               })
                               ->orderBy('name')
                               ->limit(100)
                               ->get();

        $rootFiles = File::query()
                         ->active()
                         ->files()
                         ->where('project_id', $project->id)
                         ->whereNull('dataset_id')
                         ->whereNull('directory_id')
                         ->orderBy('name')
                         ->limit(100)
                         ->get();

        return [
            ...$rootDirectories
                ->map(fn(File $directory) => $this->directoryNode(
                    $directory,
                    $project,
                    "{$parentKey}-dir-{$directory->id}",
                    $expandedNodeKeys
                ))
                ->all(),
            ...$rootFiles
                ->map(fn(File $file) => $this->fileLeaf($file, $project))
                ->all(),
        ];
    }

    private function directoryNode(
        File $directory,
        Project $project,
        string $key,
        array $expandedNodeKeys
    ): array {
        $isExpanded = in_array($key, $expandedNodeKeys, true);

        return [
            'key'         => $key,
            'kind'        => 'folder',
            'type'        => 'folder',
            'title'       => $directory->name === '' ? '/' : $directory->name,
            'icon'        => 'fas fa-folder text-warning',
            'count'       => $this->directoryChildFileCount($directory),
            'lazy'        => !$isExpanded,
            'searchTerms' => [
                $directory->name,
                $directory->path,
            ],
            'children'    => $isExpanded
                ? $this->directoryChildren($directory, $project, $key, $expandedNodeKeys)
                : [],
        ];
    }

    private function directoryChildren(
        File $directory,
        Project $project,
        string $parentKey,
        array $expandedNodeKeys
    ): array {
        $childDirectories = File::query()
                                ->active()
                                ->directories()
                                ->where('project_id', $project->id)
                                ->whereNull('dataset_id')
                                ->where('directory_id', $directory->id)
                                ->orderBy('name')
                                ->limit(100)
                                ->get();

        $childFiles = File::query()
                          ->active()
                          ->files()
                          ->where('project_id', $project->id)
                          ->whereNull('dataset_id')
                          ->where('directory_id', $directory->id)
                          ->orderBy('name')
                          ->limit(100)
                          ->get();

        return [
            ...$childDirectories
                ->map(fn(File $childDirectory) => $this->directoryNode(
                    $childDirectory,
                    $project,
                    "{$parentKey}-dir-{$childDirectory->id}",
                    $expandedNodeKeys
                ))
                ->all(),
            ...$childFiles
                ->map(fn(File $file) => $this->fileLeaf($file, $project))
                ->all(),
        ];
    }

    private function directoryChildFileCount(File $directory): int
    {
        return File::query()
                   ->active()
                   ->files()
                   ->where('project_id', $directory->project_id)
                   ->whereNull('dataset_id')
                   ->where('directory_id', $directory->id)
                   ->count();
    }

    private function fileLeaf(File $file, Project $project): array
    {
        return [
            'key'         => "file-{$file->id}",
            'kind'        => 'leaf',
            'type'        => 'file',
            'title'       => $file->name,
            'icon'        => $this->fileIcon($file),
            'badge'       => 'File',
            'project'     => $project->name,
            'location'    => "{$project->name} > Files > {$file->path}",
            'description' => $file->description ?? $file->summary ?? '',
            'tags'        => [],
            'experiment'  => null,
            'dateBucket'  => $this->dateBucket($file->updated_at),
            'dateLabel'   => $this->dateLabel($file->updated_at),
            'url'         => route('projects.files.show', [$project, $file]),
            'searchTerms' => [
                $file->name,
                $file->description ?? '',
                $file->summary ?? '',
                $file->path,
                $file->mime_type,
            ],
        ];
    }

    private function tagsFor(object $model): array
    {
        if (!method_exists($model, 'relationLoaded') || !$model->relationLoaded('tags')) {
            return [];
        }

        return collect($model->tags ?? [])
            ->pluck('name')
            ->filter()
            ->values()
            ->all();
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

    private function dateBucket($date): string
    {
        if ($date === null) {
            return 'this-year';
        }

        if ($date->isToday()) {
            return 'today';
        }

        if ($date->greaterThanOrEqualTo(now()->subDays(7))) {
            return 'last-7-days';
        }

        if ($date->greaterThanOrEqualTo(now()->subDays(30))) {
            return 'last-30-days';
        }

        return 'this-year';
    }

    private function dateLabel($date): string
    {
        if ($date === null) {
            return 'Date unavailable';
        }

        return 'Updated '.$date->diffForHumans();
    }
}
