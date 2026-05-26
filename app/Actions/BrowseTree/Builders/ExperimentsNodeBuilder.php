<?php

namespace App\Actions\BrowseTree\Builders;

use App\Actions\BrowseTree\Contracts\BrowseTreeNodeBuilder;
use App\Actions\BrowseTree\Support\BrowseTreeDates;
use App\Actions\BrowseTree\Support\BrowseTreeNode;
use App\Actions\BrowseTree\Support\BrowseTreeTags;
use App\DTO\BrowseTree\BrowseTreeContext;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;

class ExperimentsNodeBuilder implements BrowseTreeNodeBuilder
{
    public function key(string $projectKey): string
    {
        return "{$projectKey}-experiments";
    }

    public function type(): string
    {
        return 'experiment';
    }

    public function title(): string
    {
        return 'Experiments';
    }

    public function icon(): string
    {
        return 'fas fa-flask text-purple';
    }

    public function count(Project $project, BrowseTreeContext $context): int
    {
        return Experiment::query()
                         ->where('project_id', $project->id)
                         ->count();
    }

    public function children(Project $project, string $projectKey, BrowseTreeContext $context): array
    {
        $parentKey = $this->key($projectKey);

        return Experiment::query()
                         ->where('project_id', $project->id)
                         ->orderBy('name')
                         ->limit(100)
                         ->get()
                         ->map(fn(Experiment $experiment) => $this->experimentNode(
                             experiment: $experiment,
                             project: $project,
                             key: "{$parentKey}-experiment-{$experiment->id}",
                             context: $context,
                         ))
                         ->all();
    }

    private function experimentNode(
        Experiment $experiment,
        Project $project,
        string $key,
        BrowseTreeContext $context,
    ): array {
        $isExpanded = $context->isExpanded($key);

        return BrowseTreeNode::folder(
            key: $key,
            title: $experiment->name,
            icon: 'fas fa-flask text-purple',
            count: null,
            lazy: !$isExpanded,
            children: $isExpanded
                ? [
                    $this->experimentSamplesNode($experiment, $project, "{$key}-samples"),
                    $this->experimentComputationsNode($experiment, $project, "{$key}-computations"),
                ]
                : [],
            searchTerms: [
                $experiment->name,
                $experiment->description ?? '',
                $experiment->summary ?? '',
            ],
        );
    }

    private function experimentSamplesNode(Experiment $experiment, Project $project, string $key): array
    {
        $samples = $experiment->experimental_entities()
                              ->with(['experiments'])
                              ->orderBy('name')
                              ->limit(100)
                              ->get();

        return BrowseTreeNode::folder(
            key: $key,
            title: 'Samples',
            icon: 'fas fa-vials text-success',
            count: $samples->count(),
            children: $samples->map(fn(Entity $entity) => $this->entityLeaf($entity, $project, 'sample'))->all(),
        );
    }

    private function experimentComputationsNode(Experiment $experiment, Project $project, string $key): array
    {
        $computations = $experiment->computational_entities()
                                   ->with(['experiments'])
                                   ->orderBy('name')
                                   ->limit(100)
                                   ->get();

        return BrowseTreeNode::folder(
            key: $key,
            title: 'Computations',
            icon: 'fas fa-cogs text-primary',
            count: $computations->count(),
            children: $computations->map(fn(Entity $entity) => $this->entityLeaf($entity, $project, 'computation'))->all(),
        );
    }

    private function entityLeaf(Entity $entity, Project $project, string $type): array
    {
        $experiment = $entity->experiments->first();
        $isComputation = $type === 'computation';

        return BrowseTreeNode::leaf(
            key: "{$type}-{$entity->id}",
            type: $type,
            title: $entity->name,
            icon: $isComputation ? 'fas fa-microchip text-primary' : 'fas fa-vial text-success',
            badge: $isComputation ? 'Computation' : 'Sample',
            project: $project->name,
            location: $experiment === null
                ? "{$project->name} > ".($isComputation ? 'Computations' : 'Samples')
                : "{$project->name} > Experiments > {$experiment->name} > ".($isComputation ? 'Computations' : 'Samples'),
            description: $entity->description ?? $entity->summary ?? '',
            tags: BrowseTreeTags::forModel($entity),
            experiment: $experiment?->name,
            dateBucket: BrowseTreeDates::bucket($entity->updated_at),
            dateLabel: BrowseTreeDates::label($entity->updated_at),
            url: $isComputation
                ? route('projects.computations.entities.show', [$project, $entity])
                : route('projects.entities.show', [$project, $entity]),
            searchTerms: [
                $entity->name,
                $entity->description ?? '',
                $entity->summary ?? '',
                $experiment?->name ?? '',
            ],
        );
    }
}
