<?php

namespace App\Actions\BrowseTree\Support;

use Illuminate\Support\Collection;

class BrowseTreeGrouper
{
    public function byType(array $tree): array
    {
        $leaves = collect($this->flattenLeaves($tree));

        return collect($this->typeDefinitions())
            ->map(function (array $definition, string $type) use ($leaves) {
                $itemsOfType = $leaves->where('type', $type)->values();

                $projectGroups = $itemsOfType
                    ->groupBy(fn(array $item) => $item['project'] ?? 'No Project')
                    ->map(function (Collection $projectItems, string $projectName) use ($type) {
                        return BrowseTreeNode::folder(
                            key: 'group-type-'.$type.'-project-'.str($projectName)->slug(),
                            title: $projectName,
                            icon: 'fas fa-folder text-warning',
                            count: $projectItems->count(),
                            children: $projectItems->values()->all(),
                            searchTerms: [$projectName, $type],
                        );
                    })
                    ->values()
                    ->all();

                return BrowseTreeNode::folder(
                    key: 'group-type-'.$type,
                    title: $definition['title'],
                    icon: $definition['icon'],
                    count: $itemsOfType->count(),
                    children: $projectGroups,
                    searchTerms: [$type, $definition['title']],
                );
            })
            ->filter(fn(array $node) => count($node['children'] ?? []) > 0)
            ->values()
            ->all();
    }

    public function flattenLeaves(array $nodes): array
    {
        $leaves = [];

        foreach ($nodes as $node) {
            if (in_array(($node['kind'] ?? null), ['action', 'message'], true)) {
                continue;
            }

            if (empty($node['children'])) {
                if (($node['kind'] ?? 'folder') !== 'folder') {
                    $leaves[] = $node;
                }

                continue;
            }

            $leaves = [...$leaves, ...$this->flattenLeaves($node['children'])];
        }

        return $leaves;
    }

    private function typeDefinitions(): array
    {
        return [
            'sample' => [
                'title' => 'Samples',
                'icon' => 'fas fa-vials text-success',
            ],
            'computation' => [
                'title' => 'Computations',
                'icon' => 'fas fa-cogs text-primary',
            ],
            'file' => [
                'title' => 'Files',
                'icon' => 'fas fa-file-alt text-secondary',
            ],
            'dataset' => [
                'title' => 'Datasets',
                'icon' => 'fas fa-database text-info',
            ],
            'experiment' => [
                'title' => 'Experiments',
                'icon' => 'fas fa-flask text-purple',
            ],
            // Future:
            // 'user' => [...],
            // 'measurement' => [...],
            // 'attribute' => [...],
            // 'activity' => [...],
        ];
    }
}
