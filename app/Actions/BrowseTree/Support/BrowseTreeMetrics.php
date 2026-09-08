<?php

namespace App\Actions\BrowseTree\Support;

class BrowseTreeMetrics
{
    public function __construct(
        private readonly BrowseTreeGrouper $grouper = new BrowseTreeGrouper(),
    ) {
    }

    public function countLeaves(array $nodes): int
    {
        $count = 0;

        foreach ($nodes as $node) {
            if (in_array(($node['kind'] ?? null), ['action', 'message'], true)) {
                continue;
            }

            if (empty($node['children'])) {
                $count++;
            } else {
                $count += $this->countLeaves($node['children']);
            }
        }

        return $count;
    }

    public function typeCounts(array $nodes): array
    {
        $counts = [
            'sample' => 0,
            'computation' => 0,
            'file' => 0,
            'dataset' => 0,
            'experiment' => 0,
            'user' => 0,
            'measurement' => 0,
            'attribute' => 0,
            'activity' => 0,
        ];

        foreach ($this->grouper->flattenLeaves($nodes) as $item) {
            if (isset($counts[$item['type']])) {
                $counts[$item['type']]++;
            }
        }

        return $counts;
    }

    public function availableTags(array $nodes): array
    {
        return collect($this->grouper->flattenLeaves($nodes))
            ->flatMap(fn(array $node) => $node['tags'] ?? [])
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    public function availableExperiments(array $nodes): array
    {
        return collect($this->grouper->flattenLeaves($nodes))
            ->pluck('experiment')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }
}
