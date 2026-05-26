<?php

namespace App\Actions\BrowseTree\Builders;

use App\Actions\BrowseTree\Contracts\BrowseTreeNodeBuilder;
use App\Actions\BrowseTree\Support\BrowseTreeDates;
use App\Actions\BrowseTree\Support\BrowseTreeNode;
use App\Actions\BrowseTree\Support\BrowseTreeTags;
use App\DTO\BrowseTree\BrowseTreeContext;
use App\Models\Entity;
use App\Models\Project;

class SamplesNodeBuilder implements BrowseTreeNodeBuilder
{
    public function key(string $projectKey): string
    {
        return "{$projectKey}-samples";
    }

    public function type(): string
    {
        return 'sample';
    }

    public function title(): string
    {
        return 'Samples';
    }

    public function icon(): string
    {
        return 'fas fa-vials text-success';
    }

    public function count(Project $project, BrowseTreeContext $context): int
    {
        return Entity::query()
                     ->where('project_id', $project->id)
                     ->whereNull('dataset_id')
                     ->where('category', 'experimental')
                     ->count();
    }

    public function children(Project $project, string $projectKey, BrowseTreeContext $context): array
    {
        return Entity::query()
                     ->with(['experiments'])
                     ->where('project_id', $project->id)
                     ->whereNull('dataset_id')
                     ->where('category', 'experimental')
                     ->orderBy('name')
                     ->limit(100)
                     ->get()
                     ->map(fn(Entity $entity) => $this->leaf($entity, $project))
                     ->all();
    }

    private function leaf(Entity $entity, Project $project): array
    {
        $experiment = $entity->experiments->first();

        return BrowseTreeNode::leaf(
            key: "sample-{$entity->id}",
            type: 'sample',
            title: $entity->name,
            icon: 'fas fa-vial text-success',
            badge: 'Sample',
            project: $project->name,
            location: $experiment === null
                ? "{$project->name} > Samples"
                : "{$project->name} > Experiments > {$experiment->name} > Samples",
            description: $entity->description ?? $entity->summary ?? '',
            tags: BrowseTreeTags::forModel($entity),
            experiment: $experiment?->name,
            dateBucket: BrowseTreeDates::bucket($entity->updated_at),
            dateLabel: BrowseTreeDates::label($entity->updated_at),
            url: route('projects.entities.show', [$project, $entity]),
            searchTerms: [
                $entity->name,
                $entity->description ?? '',
                $entity->summary ?? '',
                $experiment?->name ?? '',
            ],
        );
    }
}
