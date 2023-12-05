<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class HealthcheckServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Health::checks([
//            PingCheck::new()->url(config('app.url')),
//            UsedDiskSpaceCheck::new()->filesystemName(config('filesystems.disks.mcfs.root'))
//                              ->warnWhenUsedSpaceIsAbovePercentage(85)
//                              ->failWhenUsedSpaceIsAbovePercentage(90),
            DatabaseCheck::new(),
        ]);
    }
}
