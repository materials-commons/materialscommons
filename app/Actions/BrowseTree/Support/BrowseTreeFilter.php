<?php

namespace App\Actions\BrowseTree\Support;

class BrowseTreeFilter
{
    public function filter(array $nodes, array $state): array
    {
        $filtered = [];

        foreach ($nodes as $node) {
            $children = $node['children'] ?? [];
            $filteredChildren = $this->filter($children, $state);

            $isLeaf = empty($children);
            $matches = $this->nodeMatches($node, $state);

            if (($isLeaf && $matches) || (!$isLeaf && ($matches || count($filteredChildren) > 0))) {
                $node['children'] = $filteredChildren;
                $filtered[] = $node;
            }
        }

        return $filtered;
    }

    public function nodeMatches(array $node, array $state): bool
    {
        if (in_array(($node['kind'] ?? null), ['action', 'message'], true)) {
            return true;
        }

        if (($node['kind'] ?? 'folder') !== 'folder' && !in_array($node['type'], $state['selectedTypes'], true)) {
            return false;
        }

        if (!$this->matchesDate($node, $state['dateFilter'])) {
            return false;
        }

        if (!$this->matchesExperiment($node, $state['experimentFilter'])) {
            return false;
        }

        if (!$this->matchesTags($node, $state['selectedTags'])) {
            return false;
        }

        $search = trim(mb_strtolower($state['search']));

        if ($search === '') {
            return true;
        }

        $haystack = mb_strtolower(implode(' ', [
            $node['title'] ?? '',
            $node['type'] ?? '',
            $node['project'] ?? '',
            $node['location'] ?? '',
            $node['description'] ?? '',
            $node['dateLabel'] ?? '',
            $node['experiment'] ?? '',
            implode(' ', $node['tags'] ?? []),
            implode(' ', $node['searchTerms'] ?? []),
        ]));

        foreach (preg_split('/\s+/', $search) as $term) {
            if ($term !== '' && !str_contains($haystack, $term)) {
                return false;
            }
        }

        return true;
    }

    private function matchesDate(array $node, string $dateFilter): bool
    {
        if ($dateFilter === 'any' || ($node['kind'] ?? 'folder') === 'folder') {
            return true;
        }

        return ($node['dateBucket'] ?? null) === $dateFilter;
    }

    private function matchesExperiment(array $node, string $experimentFilter): bool
    {
        if ($experimentFilter === 'any' || ($node['kind'] ?? 'folder') === 'folder') {
            return true;
        }

        return ($node['experiment'] ?? null) === $experimentFilter;
    }

    private function matchesTags(array $node, array $selectedTags): bool
    {
        if (count($selectedTags) === 0 || ($node['kind'] ?? 'folder') === 'folder') {
            return true;
        }

        $nodeTags = collect($node['tags'] ?? [])
            ->map(fn(string $tag) => mb_strtolower($tag))
            ->all();

        foreach ($selectedTags as $selectedTag) {
            if (!in_array(mb_strtolower($selectedTag), $nodeTags, true)) {
                return false;
            }
        }

        return true;
    }
}
