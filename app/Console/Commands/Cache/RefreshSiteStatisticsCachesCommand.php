<?php

namespace App\Console\Commands\Cache;

use App\Models\Activity;
use App\Models\Attribute;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Support\CacheKeys;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RefreshSiteStatisticsCachesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-cache:refresh-site-statistics-caches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->refreshChartCache(
            CacheKeys::SITE_STATISTICS_USERS_CHART,
            User::class,
            'Users'
        );

        $this->refreshChartCache(
            CacheKeys::SITE_STATISTICS_PROJECTS_CHART,
            Project::class,
            'Projects'
        );

        $this->refreshChartCache(
            CacheKeys::SITE_STATISTICS_DATASETS_CHART,
            Dataset::class,
            'Datasets'
        );

        $this->refreshChartCache(
            CacheKeys::SITE_STATISTICS_ENTITIES_CHART,
            Entity::class,
            'Entities'
        );

        $this->refreshChartCache(
            CacheKeys::SITE_STATISTICS_ACTIVITIES_CHART,
            Activity::class,
            'Activities'
        );

        $this->refreshChartCache(
            CacheKeys::SITE_STATISTICS_ATTRIBUTES_CHART,
            Attribute::class,
            'Attributes'
        );

        $this->refreshChartCache(
            CacheKeys::SITE_STATISTICS_FILES_UPLOADED_CHART,
            File::class,
            'Files uploaded'
        );

        return self::SUCCESS;
    }

    private function refreshChartCache(string $cacheKey, string $modelClass, string $chartName): void
    {
        $this->info("Building {$chartName} chart cache...");

        $start = Carbon::parse($modelClass::min("created_at"));
        $end = Carbon::now();

        $rows = $modelClass::query()
                           ->selectRaw("YEAR(created_at) as year, MONTH(created_at) as month, COUNT(id) as aggregate")
                           ->where("created_at", ">=", $start)
                           ->where("created_at", "<=", $end)
                           ->groupByRaw("YEAR(created_at), MONTH(created_at)")
                           ->orderByRaw("YEAR(created_at), MONTH(created_at)")
                           ->get();

        $accumulator = 0;

        $dataPerMonth = $rows->map(function ($data) use (&$accumulator) {
            $accumulator += $data->aggregate;

            return [
                "count" => $accumulator,
                "month" => Carbon::createFromDate(
                    $data->year,
                    $data->month
                )->lastOfMonth()->format("Y-m-d"),
            ];
        });

        Cache::forever($cacheKey, [
            'start'  => $start->format("Y-m-d"),
            'labels' => $dataPerMonth->pluck("month")->toArray(),
            'counts' => $dataPerMonth->pluck("count")->toArray(),
        ]);

        $this->info("{$chartName} chart cache refreshed.");
    }
}
