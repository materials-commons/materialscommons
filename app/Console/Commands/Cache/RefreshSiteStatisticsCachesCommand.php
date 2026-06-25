<?php

namespace App\Console\Commands\Cache;

use App\Models\File;
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
        // For now there is only one cache - The site statics cache for creating
        // the files uploaded chart. This is very expensive to compute. We might
        // create other caches in the future. Anything to do with files can be
        // very expensive to compute.

        $this->info("Building files uploaded chart cache...");

        $start = Carbon::parse(File::min("created_at"));
        $end = Carbon::now();

        $rows = DB::table("files")
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

        Cache::forever(CacheKeys::SITE_STATISTICS_FILES_UPLOADED_CHART, [
            'start'  => $start->format("Y-m-d"),
            'labels' => $dataPerMonth->pluck("month")->toArray(),
            'counts' => $dataPerMonth->pluck("count")->toArray(),
        ]);

        $this->info('Files Uploaded chart cache refreshed.');

        return self::SUCCESS;
    }
}
