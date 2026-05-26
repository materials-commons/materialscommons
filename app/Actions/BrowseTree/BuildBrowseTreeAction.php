<?php

namespace App\Actions\BrowseTree;

use App\Actions\BrowseTree\Builders\ComputationsNodeBuilder;
use App\Actions\BrowseTree\Builders\DatasetsNodeBuilder;
use App\Actions\BrowseTree\Builders\ExperimentsNodeBuilder;
use App\Actions\BrowseTree\Builders\FilesNodeBuilder;
use App\Actions\BrowseTree\Builders\ProjectTreeBuilder;
use App\Actions\BrowseTree\Builders\SamplesNodeBuilder;
use App\DTO\BrowseTree\BrowseTreeContext;
use App\Models\Project;
use App\Models\User;
use App\Traits\Projects\UserProjects;

class BuildBrowseTreeAction
{
    use UserProjects;

    public function execute(
        ?Project $project,
        User $user,
        string $scope,
        array $expandedNodeKeys = [],
        bool $alwaysShowFiles = false,
        array $directoriesWithVisibleFiles = [],
        ?int $focusedProjectId = null,
    ): array {
        $context = new BrowseTreeContext(
            project: $project,
            user: $user,
            scope: $scope,
            expandedNodeKeys: $expandedNodeKeys,
            alwaysShowFiles: $alwaysShowFiles,
            directoriesWithVisibleFiles: $directoriesWithVisibleFiles,
            focusedProjectId: $focusedProjectId,
        );

        $projectTreeBuilder = new ProjectTreeBuilder($this->nodeBuilders());

        if ($scope === 'project') {
            $currentProject = $project ?? $this->focusedProjectForUser($focusedProjectId, $user);

            if ($currentProject !== null) {
                return [
                    $projectTreeBuilder->projectNode($currentProject, 'current-project', $context),
                ];
            }
        }

        return $this->getUserProjects($user->id)
                    ->take(50)
                    ->map(fn(Project $userProject) => $projectTreeBuilder->projectNode(
                        $userProject,
                        "project-{$userProject->id}",
                        $context,
                    ))
                    ->values()
                    ->all();
    }

    private function focusedProjectForUser(?int $focusedProjectId, User $user): ?Project
    {
        if ($focusedProjectId === null) {
            return null;
        }

        return $this->getUserProjects($user->id)
                    ->firstWhere('id', $focusedProjectId);
    }

    private function nodeBuilders(): array
    {
        return [
            new SamplesNodeBuilder(),
            new ComputationsNodeBuilder(),
            new FilesNodeBuilder(),
            new DatasetsNodeBuilder(),
            new ExperimentsNodeBuilder(),

            // Future extension points:
            // new UsersNodeBuilder(),
            // new MeasurementsNodeBuilder(),
            // new AttributesNodeBuilder(),
            // new ActivitiesNodeBuilder(),
        ];
    }
}
