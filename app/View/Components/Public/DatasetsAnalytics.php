<?php

namespace App\View\Components\Public;

use App\Models\Dataset;
use Illuminate\View\Component;

class DatasetsAnalytics extends Component
{
    public int    $totalDatasets;
    public int    $totalViews;
    public int    $totalDownloads;

    public array  $pubMonthLabels;
    public array  $pubMonthValues;

    public array  $topViewsNames;
    public array  $topViewsValues;

    public array  $topDownloadsNames;
    public array  $topDownloadsValues;

    public array  $licenseLabels;
    public array  $licenseValues;

    public string $storageKey;

    public function __construct(bool $isTest = false)
    {
        $dateField        = $isTest ? 'test_published_at' : 'published_at';
        $this->storageKey = 'mc_pub_index_analytics' . ($isTest ? '_test' : '');

        $datasets = Dataset::whereNotNull($dateField)
            ->whereDoesntHave('tags', fn($q) => $q->where('tags.id', config('visus.import_tag_id')))
            ->withCount(['views', 'downloads'])
            ->get();

        $this->totalDatasets  = $datasets->count();
        $this->totalViews     = $datasets->sum('views_count');
        $this->totalDownloads = $datasets->sum('downloads_count');

        // Publications per month
        $pubMonths = [];
        foreach ($datasets as $ds) {
            $mk              = $ds->{$dateField}->format('Y-m');
            $pubMonths[$mk]  = ($pubMonths[$mk] ?? 0) + 1;
        }
        ksort($pubMonths);
        $this->pubMonthLabels = array_keys($pubMonths);
        $this->pubMonthValues = array_values($pubMonths);

        // Top 10 by views
        $byViews                  = $datasets->sortByDesc('views_count')->take(10);
        $this->topViewsNames      = $byViews->pluck('name')
                                             ->map(fn($n) => mb_strlen($n) > 45 ? mb_substr($n, 0, 43) . '…' : $n)
                                             ->values()->toArray();
        $this->topViewsValues     = $byViews->pluck('views_count')->values()->toArray();

        // Top 10 by downloads
        $byDownloads                  = $datasets->sortByDesc('downloads_count')->take(10);
        $this->topDownloadsNames      = $byDownloads->pluck('name')
                                                     ->map(fn($n) => mb_strlen($n) > 45 ? mb_substr($n, 0, 43) . '…' : $n)
                                                     ->values()->toArray();
        $this->topDownloadsValues     = $byDownloads->pluck('downloads_count')->values()->toArray();

        // License distribution
        $licenseCounts = [];
        foreach ($datasets as $ds) {
            $lic                  = blank($ds->license) ? 'No License' : $ds->license;
            $licenseCounts[$lic]  = ($licenseCounts[$lic] ?? 0) + 1;
        }
        arsort($licenseCounts);
        $this->licenseLabels = array_keys($licenseCounts);
        $this->licenseValues = array_values($licenseCounts);
    }

    public function render()
    {
        return view('components.public.datasets-analytics');
    }
}
