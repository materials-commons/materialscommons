<?php

namespace App\Services\Search;

use App\Models\Project;
use Illuminate\Support\Collection;

class PlaceholderSearchService
{
    public function searchPublished(string $query, string $type = 'all', int $limitPerGroup = 5): Collection
    {
        return $this->filterGroups(collect([
            'datasets' => $this->group(
                key: 'datasets',
                label: 'Published Datasets',
                icon: 'fa-database',
                results: [
                    $this->result('dataset', 'Published dataset matching '.$query, 'A published dataset placeholder result.', '#'),
                    $this->result('dataset', 'Thermal processing dataset', 'Example published dataset metadata result.', '#'),
                ],
            ),
            'communities' => $this->group(
                key: 'communities',
                label: 'Communities',
                icon: 'fa-users',
                results: [
                    $this->result('community', 'Public community matching '.$query, 'A public community placeholder result.', '#'),
                ],
            ),
        ]), $type, $limitPerGroup);
    }

    public function searchProject(Project $project, string $query, string $type = 'all', int $limitPerGroup = 5): Collection
    {
        return $this->filterGroups(collect([
            'files' => $this->group(
                key: 'files',
                label: 'Files',
                icon: 'fa-file',
                results: [
                    $this->result('file', 'microstructure.csv', '/data/processed/microstructure.csv', '#', $project->name),
                    $this->result('file', 'experiment-notes.xlsx', '/data/notes/experiment-notes.xlsx', '#', $project->name),
                ],
            ),
            'experiments' => $this->group(
                key: 'experiments',
                label: 'Experiments',
                icon: 'fa-flask',
                results: [
                    $this->result('experiment', 'Heat treatment experiment', 'Experiment placeholder result.', '#', $project->name),
                ],
            ),
            'entities' => $this->group(
                key: 'entities',
                label: 'Samples',
                icon: 'fa-cube',
                results: [
                    $this->result('entity', 'Sample A-100', 'Sample/entity placeholder result.', '#', $project->name),
                ],
            ),
            'activities' => $this->group(
                key: 'activities',
                label: 'Processes',
                icon: 'fa-gears',
                results: [
                    $this->result('activity', 'Annealing process', 'Process/activity placeholder result.', '#', $project->name),
                ],
            ),
            'datasets' => $this->group(
                key: 'datasets',
                label: 'Datasets',
                icon: 'fa-database',
                results: [
                    $this->result('dataset', 'Project dataset', 'Dataset placeholder result.', '#', $project->name),
                ],
            ),
        ]), $type, $limitPerGroup);
    }

    public function searchAllProjects(string $query, string $type = 'all', int $limitPerGroup = 5): Collection
    {
        return $this->filterGroups(collect([
            'files' => $this->group(
                key: 'files',
                label: 'Files',
                icon: 'fa-file',
                results: [
                    $this->result('file', 'shared-results.csv', '/project-a/results/shared-results.csv', '#', 'Project A'),
                    $this->result('file', 'summary.xlsx', '/project-b/summary.xlsx', '#', 'Project B'),
                ],
            ),
            'experiments' => $this->group(
                key: 'experiments',
                label: 'Experiments',
                icon: 'fa-flask',
                results: [
                    $this->result('experiment', 'Cross-project heat study', 'Experiment placeholder result.', '#', 'Project A'),
                ],
            ),
            'entities' => $this->group(
                key: 'entities',
                label: 'Samples',
                icon: 'fa-cube',
                results: [
                    $this->result('entity', 'Sample from Project B', 'Sample placeholder result.', '#', 'Project B'),
                ],
            ),
            'activities' => $this->group(
                key: 'activities',
                label: 'Processes',
                icon: 'fa-gears',
                results: [
                    $this->result('activity', 'Rolling process', 'Process placeholder result.', '#', 'Project C'),
                ],
            ),
            'datasets' => $this->group(
                key: 'datasets',
                label: 'Datasets',
                icon: 'fa-database',
                results: [
                    $this->result('dataset', 'Internal dataset', 'Dataset placeholder result.', '#', 'Project A'),
                ],
            ),
        ]), $type, $limitPerGroup);
    }

    private function filterGroups(Collection $groups, string $type, int $limitPerGroup): Collection
    {
        if ($type !== 'all') {
            $groups = $groups->only($type);
        }

        return $groups
            ->map(function (array $group) use ($limitPerGroup) {
                $group['results'] = collect($group['results'])->take($limitPerGroup)->values()->all();
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

    private function result(
        string $type,
        string $title,
        string $subtitle,
        string $url,
        ?string $projectName = null,
    ): array {
        return [
            'type' => $type,
            'title' => $title,
            'subtitle' => $subtitle,
            'description' => 'Placeholder result. Backend search will replace this later.',
            'url' => $url,
            'project' => $projectName,
        ];
    }
}
