<?php

namespace App\DTO\BrowseTree;

use App\Models\Project;
use App\Models\User;

class BrowseTreeContext
{
    public function __construct(
        public readonly ?Project $project,
        public readonly User $user,
        public readonly string $scope,
        public readonly array $expandedNodeKeys,
        public readonly bool $alwaysShowFiles,
        public readonly array $directoriesWithVisibleFiles,
        public readonly ?int $focusedProjectId,
    ) {
    }

    public function isExpanded(string $key): bool
    {
        return in_array($key, $this->expandedNodeKeys, true);
    }

    public function shouldShowFilesForDirectory(string $directoryKey): bool
    {
        return $this->alwaysShowFiles || in_array($directoryKey, $this->directoriesWithVisibleFiles, true);
    }
}
