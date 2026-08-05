<?php

namespace App\Services\Search;

use App\Models\Activity;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Traits\Projects\UserProjects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MCSearchService
{
    use UserProjects;

    public function searchPublished(string $query, string $type = 'all', int $limitPerGroup = 10): Collection
    {
        return $this->filterGroups(collect([
            'datasets' => $this->searchPublishedDatasets($query, $limitPerGroup),
            'communities' => $this->searchPublicCommunities($query, $limitPerGroup),
        ]), $type);
    }

    public function searchProject(Project $project, string $query, string $type = 'all', int $limitPerGroup = 10): Collection
    {
        return $this->filterGroups(collect([
            'files' => $this->searchProjectFiles($project, $query, $limitPerGroup),
            'experiments' => $this->searchProjectExperiments($project, $query, $limitPerGroup),
            'entities' => $this->searchProjectEntities($project, $query, $limitPerGroup),
            'activities' => $this->searchProjectActivities($project, $query, $limitPerGroup),
            'datasets' => $this->searchProjectDatasets($project, $query, $limitPerGroup),
        ]), $type);
    }

    public function searchAllProjects(string $query, string $type = 'all', int $limitPerGroup = 10): Collection
    {
        $projectIds = $this->accessibleProjectIds();

        if ($projectIds->isEmpty()) {
            return collect();
        }

        return $this->filterGroups(collect([
            'files' => $this->searchFilesAcrossProjects($projectIds, $query, $limitPerGroup),
            'experiments' => $this->searchExperimentsAcrossProjects($projectIds, $query, $limitPerGroup),
            'entities' => $this->searchEntitiesAcrossProjects($projectIds, $query, $limitPerGroup),
            'activities' => $this->searchActivitiesAcrossProjects($projectIds, $query, $limitPerGroup),
            'datasets' => $this->searchDatasetsAcrossProjects($projectIds, $query, $limitPerGroup),
        ]), $type);
    }

    private function searchPublishedDatasets(string $query, int $limit): array
    {
        $results = Dataset::search($query)
                          ->where('is_published', true)
                          ->take($limit)
                          ->get();

        return $this->group(
            key: 'datasets',
            label: 'Published Datasets',
            icon: 'fa-database',
            results: $results->map(fn (Dataset $dataset) => $this->datasetResult($dataset))->all(),
        );
    }

    private function searchPublicCommunities(string $query, int $limit): array
    {
        $results = Community::search($query)
                            ->where('public', true)
                            ->take($limit)
                            ->get();

        return $this->group(
            key: 'communities',
            label: 'Communities',
            icon: 'fa-users',
            results: $results->map(fn (Community $community) => $this->communityResult($community))->all(),
        );
    }

    private function searchProjectFiles(Project $project, string $query, int $limit): array
    {
        $results = File::search($query)
                       ->query(fn ($query) => $query->with(['directory', 'project']))
                       ->where('project_id', $project->id)
                       ->where('dataset_id', null)
                       ->where('deleted_at', null)
                       ->where('current', true)
                       ->take($limit)
                       ->get();

        return $this->group(
            key: 'files',
            label: 'Files',
            icon: 'fa-file',
            results: $results->map(fn (File $file) => $this->fileResult($file))->all(),
        );
    }

    private function searchProjectExperiments(Project $project, string $query, int $limit): array
    {
        $results = Experiment::search($query)
                             ->query(fn ($query) => $query->with('project'))
                             ->where('project_id', $project->id)
                             ->take($limit)
                             ->get();

        return $this->group(
            key: 'experiments',
            label: 'Experiments',
            icon: 'fa-flask',
            results: $results->map(fn (Experiment $experiment) => $this->experimentResult($experiment))->all(),
        );
    }

    private function searchProjectEntities(Project $project, string $query, int $limit): array
    {
        $results = Entity::search($query)
                         ->query(fn ($query) => $query->with('project'))
                         ->where('project_id', $project->id)
                         ->take($limit)
                         ->get();

        return $this->group(
            key: 'entities',
            label: 'Samples',
            icon: 'fa-cube',
            results: $results->map(fn (Entity $entity) => $this->entityResult($entity))->all(),
        );
    }

    private function searchProjectActivities(Project $project, string $query, int $limit): array
    {
        $results = Activity::search($query)
                           ->query(fn ($query) => $query->with('project'))
                           ->where('project_id', $project->id)
                           ->take($limit)
                           ->get();

        return $this->group(
            key: 'activities',
            label: 'Processes',
            icon: 'fa-gears',
            results: $results->map(fn (Activity $activity) => $this->activityResult($activity))->all(),
        );
    }

    private function searchProjectDatasets(Project $project, string $query, int $limit): array
    {
        $results = Dataset::search($query)
                          ->query(fn ($query) => $query->with('project'))
                          ->where('project_id', $project->id)
                          ->take($limit)
                          ->get();

        return $this->group(
            key: 'datasets',
            label: 'Datasets',
            icon: 'fa-database',
            results: $results->map(fn (Dataset $dataset) => $this->datasetResult($dataset))->all(),
        );
    }

    private function searchFilesAcrossProjects(Collection $projectIds, string $query, int $limit): array
    {
        $results = File::search($query)
                       ->query(fn ($query) => $query->with(['directory', 'project']))
                       ->whereIn('project_id', $projectIds->all())
                       ->where('dataset_id', null)
                       ->where('deleted_at', null)
                       ->where('current', true)
                       ->take($limit)
                       ->get();

        return $this->group(
            key: 'files',
            label: 'Files',
            icon: 'fa-file',
            results: $results->map(fn (File $file) => $this->fileResult($file))->all(),
        );
    }

    private function searchExperimentsAcrossProjects(Collection $projectIds, string $query, int $limit): array
    {
        $results = Experiment::search($query)
                             ->query(fn ($query) => $query->with('project'))
                             ->whereIn('project_id', $projectIds->all())
                             ->take($limit)
                             ->get();

        return $this->group(
            key: 'experiments',
            label: 'Experiments',
            icon: 'fa-flask',
            results: $results->map(fn (Experiment $experiment) => $this->experimentResult($experiment))->all(),
        );
    }

    private function searchEntitiesAcrossProjects(Collection $projectIds, string $query, int $limit): array
    {
        $results = Entity::search($query)
                         ->query(fn ($query) => $query->with('project'))
                         ->whereIn('project_id', $projectIds->all())
                         ->take($limit)
                         ->get();

        return $this->group(
            key: 'entities',
            label: 'Samples',
            icon: 'fa-cube',
            results: $results->map(fn (Entity $entity) => $this->entityResult($entity))->all(),
        );
    }

    private function searchActivitiesAcrossProjects(Collection $projectIds, string $query, int $limit): array
    {
        $results = Activity::search($query)
                           ->query(fn ($query) => $query->with('project'))
                           ->whereIn('project_id', $projectIds->all())
                           ->take($limit)
                           ->get();

        return $this->group(
            key: 'activities',
            label: 'Processes',
            icon: 'fa-gears',
            results: $results->map(fn (Activity $activity) => $this->activityResult($activity))->all(),
        );
    }

    private function searchDatasetsAcrossProjects(Collection $projectIds, string $query, int $limit): array
    {
        $results = Dataset::search($query)
                          ->query(fn ($query) => $query->with('project'))
                          ->whereIn('project_id', $projectIds->all())
                          ->take($limit)
                          ->get();

        return $this->group(
            key: 'datasets',
            label: 'Datasets',
            icon: 'fa-database',
            results: $results->map(fn (Dataset $dataset) => $this->datasetResult($dataset))->all(),
        );
    }

    private function accessibleProjectIds(): Collection
    {
        if (! auth()->check()) {
            return collect();
        }

        $projects = $this->getUserProjects(auth()->id());

        return $projects->pluck('id');
    }

    private function filterGroups(Collection $groups, string $type): Collection
    {
        if ($type !== 'all') {
            $groups = $groups->only($type);
        }

        return $groups
            ->map(function (array $group) {
                $group['count'] = count($group['results']);

                return $group;
            })
            ->filter(fn (array $group) => $group['count'] > 0);
    }

    private function group(string $key, string $label, string $icon, array $results): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'icon' => $icon,
            'count' => count($results),
            'results' => $results,
        ];
    }

    private function fileResult(File $file): array
    {
        $path = $this->filePath($file);

        return $this->result(
            type: 'file',
            title: $file->name,
            subtitle: $path,
            description: $file->description ?: 'File',
            url: $this->modelUrl($file),
            projectName: $file->project?->name,
        );
    }

    private function experimentResult(Experiment $experiment): array
    {
        return $this->result(
            type: 'experiment',
            title: $experiment->name,
            subtitle: 'Experiment',
            description: $experiment->description ?? '',
            url: $this->modelUrl($experiment),
            projectName: $experiment->project?->name,
        );
    }

    private function entityResult(Entity $entity): array
    {
        return $this->result(
            type: 'sample',
            title: $entity->name,
            subtitle: 'Sample',
            description: $entity->description ?? '',
            url: $this->modelUrl($entity),
            projectName: $entity->project?->name,
        );
    }

    private function activityResult(Activity $activity): array
    {
        return $this->result(
            type: 'process',
            title: $activity->name,
            subtitle: 'Process',
            description: $activity->description ?? '',
            url: $this->modelUrl($activity),
            projectName: $activity->project?->name,
        );
    }

    private function datasetResult(Dataset $dataset): array
    {
        return $this->result(
            type: 'dataset',
            title: $dataset->name,
            subtitle: $dataset->published_at ? 'Published dataset' : 'Dataset',
            description: $dataset->description ?? '',
            url: $this->modelUrl($dataset),
            projectName: $dataset->project?->name,
        );
    }

    private function communityResult(Community $community): array
    {
        return $this->result(
            type: 'community',
            title: $community->name,
            subtitle: 'Community',
            description: $community->description ?? '',
            url: $this->modelUrl($community),
        );
    }

    private function result(
        string $type,
        string $title,
        string $subtitle,
        string $description,
        string $url,
        ?string $projectName = null,
    ): array {
        return [
            'type' => $type,
            'title' => $title,
            'subtitle' => $subtitle,
            'description' => $description,
            'url' => $url,
            'project' => $projectName,
        ];
    }

    private function modelUrl(Model $model): string
    {
        if (method_exists($model, 'getScoutUrl')) {
            return $model->getScoutUrl();
        }

        if ($model instanceof Project) {
            return route('projects.show', [$model]);
        }

        return '#';
    }

    private function filePath(File $file): string
    {
        $directoryPath = $file->directory?->path;

        if (blank($directoryPath) || $directoryPath === '/') {
            return '/'.$file->name;
        }

        return rtrim($directoryPath, '/').'/'.$file->name;
    }
}
