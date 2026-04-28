<?php

namespace App\Services;

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Computes all data needed for an author/user profile page.
 * Used by both the public author page and the (internal) user profile page.
 */
class AuthorProfileService
{
    /** Datasets this user owns (published, or all if $publishedOnly = false) */
    public Collection $ownedDatasets;

    /** Published datasets where this user appears in ds_authors but is not the owner */
    public Collection $includedDatasets;

    /** Union of owned + included */
    public Collection $allDatasets;

    /**
     * Deduplicated papers across all datasets.
     * Each item: ['paper' => Paper, 'datasets' => Collection of Dataset]
     */
    public Collection $papers;

    /** tag_name => count, from owned datasets, sorted desc */
    public array $ownedTags;

    /** tag_name => count, from included datasets, sorted desc */
    public array $includedTags;

    /** tag_name => count, combined, sorted desc */
    public array $allTags;

    /** name => ['count' => int, 'user' => User|null], sorted by count desc */
    public array $coAuthors;

    /** Sum of views_count across owned datasets */
    public int $totalViews;

    /** Sum of downloads_count across owned datasets */
    public int $totalDownloads;

    /** 'Y-m' => count, publication timeline for owned datasets */
    public array $pubTimeline;

    /** Top tag names for chart (max 15) */
    public array $chartTagNames;

    /** Corresponding tag counts */
    public array $chartTagCounts;

    /** Top co-author names for chart (max 15) */
    public array $chartCoauthorNames;

    /** Corresponding shared dataset counts */
    public array $chartCoauthorCounts;

    public function __construct(public readonly User $user, bool $publishedOnly = true)
    {
        $this->ownedDatasets = Dataset::where('owner_id', $user->id)
            ->when($publishedOnly, fn($q) => $q->whereNotNull('published_at'))
            ->with(['tags', 'papers'])
            ->withCount(['views', 'downloads'])
            ->orderByDesc('published_at')
            ->get();

        $this->includedDatasets = Dataset::where('owner_id', '!=', $user->id)
            ->when($publishedOnly, fn($q) => $q->whereNotNull('published_at'))
            ->whereRaw('ds_authors COLLATE utf8mb4_general_ci LIKE ?', ['%"' . $user->name . '"%'])
            ->with(['tags', 'papers'])
            ->withCount(['views', 'downloads'])
            ->orderByDesc('published_at')
            ->get();

        $this->allDatasets = $this->ownedDatasets->merge($this->includedDatasets);

        $this->totalViews     = (int) $this->ownedDatasets->sum('views_count');
        $this->totalDownloads = (int) $this->ownedDatasets->sum('downloads_count');

        $this->ownedTags    = $this->computeTags($this->ownedDatasets);
        $this->includedTags = $this->computeTags($this->includedDatasets);
        $this->allTags      = $this->mergeTags($this->ownedTags, $this->includedTags);

        $this->papers = $this->computePapers();
        $this->coAuthors = $this->computeCoAuthors();

        // Publication timeline (owned only)
        $timeline = [];
        foreach ($this->ownedDatasets as $ds) {
            if ($ds->published_at) {
                $mk           = $ds->published_at->format('Y-m');
                $timeline[$mk] = ($timeline[$mk] ?? 0) + 1;
            }
        }
        ksort($timeline);
        $this->pubTimeline = $timeline;

        // Chart data — top 15 tags
        $topTags                = array_slice($this->allTags, 0, 15, true);
        $this->chartTagNames    = array_keys($topTags);
        $this->chartTagCounts   = array_values($topTags);

        // Chart data — top 15 co-authors
        $topCoauthors               = array_slice($this->coAuthors, 0, 15, true);
        $this->chartCoauthorNames   = array_keys($topCoauthors);
        $this->chartCoauthorCounts  = array_column(array_values($topCoauthors), 'count');
    }

    // ── helpers ──────────────────────────────────────────────────────────────────

    private function computeTags(Collection $datasets): array
    {
        $tags = [];
        foreach ($datasets as $ds) {
            foreach ($ds->tags as $tag) {
                $tags[$tag->name] = ($tags[$tag->name] ?? 0) + 1;
            }
        }
        arsort($tags);
        return $tags;
    }

    private function mergeTags(array ...$tagArrays): array
    {
        $merged = [];
        foreach ($tagArrays as $tags) {
            foreach ($tags as $name => $count) {
                $merged[$name] = ($merged[$name] ?? 0) + $count;
            }
        }
        arsort($merged);
        return $merged;
    }

    private function computePapers(): Collection
    {
        $map = []; // paper_id => ['paper' => Paper, 'datasets' => [Dataset, ...]]
        foreach ($this->allDatasets as $ds) {
            foreach ($ds->papers as $paper) {
                if (!isset($map[$paper->id])) {
                    $map[$paper->id] = ['paper' => $paper, 'datasets' => collect()];
                }
                $map[$paper->id]['datasets']->push($ds);
            }
        }
        return collect(array_values($map))->sortBy(fn($item) => $item['paper']->name);
    }

    private function computeCoAuthors(): array
    {
        $coAuthors = [];
        $myName    = $this->user->name;

        foreach ($this->allDatasets as $ds) {
            if (is_null($ds->ds_authors)) {
                continue;
            }
            foreach ($ds->ds_authors as $author) {
                $name = trim($author['name'] ?? '');
                if ($name === '' || $name === $myName) {
                    continue;
                }
                if (!isset($coAuthors[$name])) {
                    $coAuthors[$name] = ['count' => 0, 'datasets' => collect(), 'user' => null];
                }
                $coAuthors[$name]['count']++;
                $coAuthors[$name]['datasets']->push($ds);
            }
        }

        // Resolve MC accounts for co-authors
        if (!empty($coAuthors)) {
            $mcUsers = User::whereIn('name', array_keys($coAuthors))
                ->get(['id', 'name', 'slug']);
            foreach ($mcUsers as $mcUser) {
                if (isset($coAuthors[$mcUser->name])) {
                    $coAuthors[$mcUser->name]['user'] = $mcUser;
                }
            }
        }

        uasort($coAuthors, fn($a, $b) => $b['count'] <=> $a['count']);
        return $coAuthors;
    }
}
