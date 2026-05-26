<?php

namespace App\Actions\BrowseTree\Contracts;

use App\DTO\BrowseTree\BrowseTreeContext;
use App\Models\Project;

interface BrowseTreeNodeBuilder
{
    public function key(string $projectKey): string;

    public function type(): string;

    public function title(): string;

    public function icon(): string;

    public function count(Project $project, BrowseTreeContext $context): int;

    public function children(Project $project, string $projectKey, BrowseTreeContext $context): array;
}
