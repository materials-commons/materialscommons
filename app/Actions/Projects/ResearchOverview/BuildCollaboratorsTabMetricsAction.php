<?php

namespace App\Actions\Projects\ResearchOverview;

use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BuildCollaboratorsTabMetricsAction
{
    public function execute(Project $project): array
    {
        return Cache::remember(
            "project:{$project->id}:research-overview:collaborators:v1",
            now()->addMinutes(5),
            fn() => $this->build($project)
        );
    }

    private function build(Project $project): array
    {
        $project->loadMissing(['owner', 'team.members', 'team.admins']);

        $owner = $project->owner;
        $admins = collect($project->team?->admins ?? collect())->values();
        $members = collect($project->team?->members ?? collect())->values();

        $teamUsers = $admins
            ->merge($members)
            ->filter()
            ->unique('id')
            ->values();

        $datasets = Dataset::query()
                           ->where('project_id', $project->id)
                           ->withCount(['files', 'experiments', 'views', 'downloads'])
                           ->orderByDesc('updated_at')
                           ->get([
                               'id',
                               'name',
                               'project_id',
                               'owner_id',
                               'authors',
                               'ds_authors',
                               'published_at',
                               'updated_at',
                           ]);

        $datasetAuthors = $this->datasetAuthors($datasets);
        $datasetOwners = $datasets->pluck('owner_id')->filter()->unique()->values();

        $teamUserIds = $teamUsers->pluck('id')->filter()->values();
        $adminIds = $admins->pluck('id')->filter()->values();
        $memberIds = $members->pluck('id')->filter()->values();

        $ownerIsAdmin = $owner !== null && $adminIds->contains($owner->id);
        $ownerIsMember = $owner !== null && $memberIds->contains($owner->id);

        $datasetsWithoutAuthors = $datasets
            ->filter(fn($dataset) => $this->datasetAuthorNames($dataset)->isEmpty())
            ->values();

        $publishedDatasetsWithoutAuthors = $datasetsWithoutAuthors
            ->filter(fn($dataset) => filled($dataset->published_at))
            ->values();

        $externalDatasetAuthors = $datasetAuthors
            ->reject(function ($author) use ($owner, $teamUsers) {
                $authorName = Str::lower(trim((string) $author['name']));

                if ($owner !== null && $authorName === Str::lower(trim((string) $owner->name))) {
                    return true;
                }

                return $teamUsers->contains(function ($user) use ($authorName) {
                    return $authorName === Str::lower(trim((string) $user->name));
                });
            })
            ->values();

        $teamCoverageItems = collect([
            [
                'label' => 'Owner',
                'value' => $owner === null ? 0 : 1,
                'color' => 'primary',
                'hint' => 'project owner assigned',
            ],
            [
                'label' => 'Admins',
                'value' => $admins->count(),
                'color' => 'warning',
                'hint' => 'project administrators',
            ],
            [
                'label' => 'Members',
                'value' => $members->count(),
                'color' => 'primary',
                'hint' => 'project members',
            ],
            [
                'label' => 'Dataset Authors',
                'value' => $datasetAuthors->count(),
                'color' => 'success',
                'hint' => 'unique named dataset contributors',
            ],
        ]);

        $datasetCoverageItems = collect([
            [
                'label' => 'With Authors',
                'value' => $datasets->count() - $datasetsWithoutAuthors->count(),
                'color' => 'success',
                'hint' => 'datasets with author metadata',
            ],
            [
                'label' => 'Missing Authors',
                'value' => $datasetsWithoutAuthors->count(),
                'color' => 'warning',
                'hint' => 'datasets without author metadata',
            ],
            [
                'label' => 'Published Missing Authors',
                'value' => $publishedDatasetsWithoutAuthors->count(),
                'color' => 'danger',
                'hint' => 'published datasets without authors',
            ],
            [
                'label' => 'External Authors',
                'value' => $externalDatasetAuthors->count(),
                'color' => 'info',
                'hint' => 'dataset authors not listed on project team',
            ],
        ]);

        $needsReview = collect();

        if ($owner === null) {
            $needsReview->push([
                'label' => 'Project has no owner',
                'type' => 'Project',
                'severity' => 'danger',
                'hint' => 'Assign or verify the project owner.',
            ]);
        }

        if ($admins->isEmpty()) {
            $needsReview->push([
                'label' => 'No project admins',
                'type' => 'Team',
                'severity' => 'warning',
                'hint' => 'Consider adding at least one admin besides the owner.',
            ]);
        }

        if ($members->isEmpty()) {
            $needsReview->push([
                'label' => 'No project members',
                'type' => 'Team',
                'severity' => 'secondary',
                'hint' => 'Invite collaborators if this is a shared project.',
            ]);
        }

        if ($publishedDatasetsWithoutAuthors->isNotEmpty()) {
            $needsReview->push([
                'label' => "{$publishedDatasetsWithoutAuthors->count()} published dataset(s) missing authors",
                'type' => 'Datasets',
                'severity' => 'danger',
                'hint' => 'Published datasets should include author metadata.',
            ]);
        }

        if ($datasetsWithoutAuthors->isNotEmpty()) {
            $needsReview->push([
                'label' => "{$datasetsWithoutAuthors->count()} dataset(s) missing authors",
                'type' => 'Datasets',
                'severity' => 'warning',
                'hint' => 'Add author metadata before publication.',
            ]);
        }

        if (!$ownerIsAdmin && !$ownerIsMember && $owner !== null) {
            $needsReview->push([
                'label' => 'Owner is not listed as team member/admin',
                'type' => 'Team',
                'severity' => 'info',
                'hint' => 'This may be expected, but it is worth verifying.',
            ]);
        }

        $totalPeopleSignals = max(1, $teamUsers->count() + ($owner === null ? 0 : 1) + $datasetAuthors->count());
        $teamReadiness = collect([
            $owner !== null,
            $admins->isNotEmpty(),
            $members->isNotEmpty() || $datasetAuthors->isNotEmpty(),
            $datasetsWithoutAuthors->isEmpty(),
        ]);

        $readinessPercent = round(($teamReadiness->filter()->count() / $teamReadiness->count()) * 100);

        return [
            'owner' => $owner,
            'admins' => $admins,
            'members' => $members,
            'teamUsers' => $teamUsers,
            'datasetAuthors' => $datasetAuthors,
            'externalDatasetAuthors' => $externalDatasetAuthors,
            'datasets' => $datasets,
            'datasetsWithoutAuthors' => $datasetsWithoutAuthors,
            'publishedDatasetsWithoutAuthors' => $publishedDatasetsWithoutAuthors,
            'teamCoverageItems' => $teamCoverageItems,
            'datasetCoverageItems' => $datasetCoverageItems,
            'needsReview' => $needsReview,
            'ownerCount' => $owner === null ? 0 : 1,
            'adminCount' => $admins->count(),
            'memberCount' => $members->count(),
            'teamUserCount' => $teamUsers->count(),
            'datasetAuthorCount' => $datasetAuthors->count(),
            'externalDatasetAuthorCount' => $externalDatasetAuthors->count(),
            'datasetOwnerCount' => $datasetOwners->count(),
            'datasetsMissingAuthorsCount' => $datasetsWithoutAuthors->count(),
            'publishedDatasetsMissingAuthorsCount' => $publishedDatasetsWithoutAuthors->count(),
            'totalPeopleSignals' => $totalPeopleSignals,
            'readinessPercent' => $readinessPercent,
        ];
    }

    private function datasetAuthors(Collection $datasets): Collection
    {
        return $datasets
            ->flatMap(fn($dataset) => $this->datasetAuthorRows($dataset))
            ->filter(fn($author) => filled($author['name'] ?? null))
            ->map(function ($author) {
                $name = trim((string) $author['name']);

                return [
                    'key' => Str::lower($name),
                    'name' => $name,
                    'affiliations' => trim((string) ($author['affiliations'] ?? '')),
                    'dataset_count' => 1,
                ];
            })
            ->groupBy('key')
            ->map(function ($rows) {
                $first = $rows->first();

                return [
                    'name' => $first['name'],
                    'affiliations' => $first['affiliations'],
                    'dataset_count' => $rows->sum('dataset_count'),
                ];
            })
            ->sortBy('name')
            ->values();
    }

    private function datasetAuthorRows(Dataset $dataset): Collection
    {
        if (is_array($dataset->ds_authors)) {
            return collect($dataset->ds_authors)
                ->map(fn($author) => [
                    'name' => $author['name'] ?? null,
                    'affiliations' => $author['affiliations'] ?? '',
                ]);
        }

        return $this->authorsStringToRows($dataset->authors);
    }

    private function datasetAuthorNames(Dataset $dataset): Collection
    {
        return $this->datasetAuthorRows($dataset)
                    ->pluck('name')
                    ->filter(fn($name) => filled($name))
                    ->map(fn($name) => trim((string) $name))
                    ->values();
    }

    private function authorsStringToRows(?string $authors): Collection
    {
        if (blank($authors)) {
            return collect();
        }

        return Str::of($authors)
                  ->explode(';')
                  ->map(fn($name) => [
                      'name' => trim((string) $name),
                      'affiliations' => '',
                  ])
                  ->filter(fn($author) => filled($author['name']))
                  ->values();
    }
}
