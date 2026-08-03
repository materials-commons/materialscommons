<?php

namespace App\Actions\BrowseTree\Builders;

use App\Actions\BrowseTree\Contracts\BrowseTreeNodeBuilder;
use App\Actions\BrowseTree\Support\BrowseTreeDates;
use App\Actions\BrowseTree\Support\BrowseTreeNode;
use App\Actions\BrowseTree\Support\BrowseTreeTags;
use App\DTO\BrowseTree\BrowseTreeContext;
use App\Models\Dataset;
use App\Models\Project;

class DatasetsNodeBuilder implements BrowseTreeNodeBuilder
{
    public function key(string $projectKey): string
    {
        return "{$projectKey}-datasets";
    }

    public function type(): string
    {
        return 'dataset';
    }

    public function title(): string
    {
        return 'Datasets';
    }

    public function icon(): string
    {
        return 'fas fa-database text-info';
    }

    public function count(Project $project, BrowseTreeContext $context): int
    {
        return Dataset::query()
                      ->where('project_id', $project->id)
                      ->count();
    }

    public function children(Project $project, string $projectKey, BrowseTreeContext $context): array
    {
        return Dataset::query()
                      ->with(['tags'])
                      ->where('project_id', $project->id)
                      ->orderByDesc('updated_at')
                      ->limit(100)
                      ->get()
                      ->map(fn(Dataset $dataset) => $this->leaf($dataset, $project))
                      ->all();
    }

    private function leaf(Dataset $dataset, Project $project): array
    {
        return BrowseTreeNode::leaf(
            key: "dataset-{$dataset->id}",
            type: 'dataset',
            title: $dataset->name,
            icon: 'fas fa-database text-info',
            badge: $dataset->isPublished() ? 'Published Dataset' : 'Dataset',
            project: $project->name,
            location: "{$project->name} > Datasets",
            description: $dataset->description ?? $dataset->summary ?? '',
            tags: BrowseTreeTags::forModel($dataset),
            experiment: null,
            dateBucket: BrowseTreeDates::bucket($dataset->updated_at),
            dateLabel: BrowseTreeDates::label($dataset->updated_at),
            url: route('projects.datasets.show', [$project, $dataset]),
            searchTerms: [
                $dataset->name,
                $dataset->description ?? '',
                $dataset->summary ?? '',
            ],
        );
    }
}
