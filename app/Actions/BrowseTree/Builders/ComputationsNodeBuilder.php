<?php

namespace App\Actions\BrowseTree\Builders;

use App\Actions\BrowseTree\Contracts\BrowseTreeNodeBuilder;
use App\Actions\BrowseTree\Support\BrowseTreeDates;
use App\Actions\BrowseTree\Support\BrowseTreeNode;
use App\Actions\BrowseTree\Support\BrowseTreeTags;
use App\DTO\BrowseTree\BrowseTreeContext;
use App\Models\Entity;
use App\Models\Project;

class ComputationsNodeBuilder implements BrowseTreeNodeBuilder
{
    public function key(string $projectKey): string
    {
        return "{$projectKey}-computations";
    }

    public function type(): string
    {
        return 'computation';
    }

    public function title(): string
    {
        return 'Computations';
    }

    public function icon(): string
    {
        return 'fas fa-cogs text-primary';
    }

    public function count(Project $project, BrowseTreeContext $context): int
    {
        return Entity::query()
                     ->where('project_id', $project->id)
                     ->whereNull('dataset_id')
                     ->where('category', 'computational')
                     ->count();
    }

    public function children(Project $project, string $projectKey, BrowseTreeContext $context): array
    {
        return Entity::query()
                     ->with(['experiments'])
                     ->where('project_id', $project->id)
                     ->whereNull('dataset_id')
                     ->where('category', 'computational')
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
            key: "computation-{$entity->id}",
            type: 'computation',
            title: $entity->name,
            icon: 'fas fa-microchip text-primary',
            badge: 'Computation',
            project: $project->name,
            location: $experiment === null
                ? "{$project->name} > Computations"
                : "{$project->name} > Experiments > {$experiment->name} > Computations",
            description: $entity->description ?? $entity->summary ?? '',
            tags: BrowseTreeTags::forModel($entity),
            experiment: $experiment?->name,
            dateBucket: BrowseTreeDates::bucket($entity->updated_at),
            dateLabel: BrowseTreeDates::label($entity->updated_at),
            url: route('projects.computations.entities.show', [$project, $entity]),
            searchTerms: [
                $entity->name,
                $entity->description ?? '',
                $entity->summary ?? '',
                $experiment?->name ?? '',
            ],
        );
    }
}
