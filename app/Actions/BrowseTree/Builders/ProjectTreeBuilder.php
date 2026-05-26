<?php

namespace App\Actions\BrowseTree\Builders;

use App\Actions\BrowseTree\Contracts\BrowseTreeNodeBuilder;
use App\Actions\BrowseTree\Support\BrowseTreeNode;
use App\DTO\BrowseTree\BrowseTreeContext;
use App\Models\Project;

class ProjectTreeBuilder
{
    /**
     * @param array<int, BrowseTreeNodeBuilder> $nodeBuilders
     */
    public function __construct(
        private readonly array $nodeBuilders,
    ) {
    }

    public function projectNode(Project $project, string $projectKey, BrowseTreeContext $context): array
    {
        $isExpanded = $context->isExpanded($projectKey);
        $projectTitle = $this->projectTitle($project);

        return BrowseTreeNode::folder(
            key: $projectKey,
            title: $projectTitle,
            icon: 'fas fa-folder text-warning',
            count: null,
            lazy: !$isExpanded,
            children: $isExpanded ? $this->projectCategoryNodes($project, $projectKey, $context) : [],
            searchTerms: [
                $projectTitle,
                $project->description ?? '',
                $project->summary ?? '',
            ],
            projectKey: $projectKey,
        );
    }

    private function projectCategoryNodes(Project $project, string $projectKey, BrowseTreeContext $context): array
    {
        return collect($this->nodeBuilders)
            ->map(fn(BrowseTreeNodeBuilder $builder) => $this->categoryNode($builder, $project, $projectKey, $context))
            ->values()
            ->all();
    }

    private function categoryNode(
        BrowseTreeNodeBuilder $builder,
        Project $project,
        string $projectKey,
        BrowseTreeContext $context,
    ): array {
        $key = $builder->key($projectKey);
        $isExpanded = $context->isExpanded($key);

        return BrowseTreeNode::folder(
            key: $key,
            title: $builder->title(),
            icon: $builder->icon(),
            count: $builder->count($project, $context),
            lazy: !$isExpanded,
            children: $isExpanded ? $builder->children($project, $projectKey, $context) : [],
        );
    }

    private function projectTitle(Project $project): string
    {
        $name = trim((string) ($project->name ?? ''));

        if ($name !== '') {
            return $name;
        }

        return "Untitled Project #{$project->id}";
    }
}
