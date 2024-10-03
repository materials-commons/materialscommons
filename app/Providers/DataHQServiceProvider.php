<?php

namespace App\Providers;

use App\Services\DataHQ\SamplesHQContextStateStore;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DataHQServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('sampleshq', function (Application $app) {
            return new SamplesHQContextStateStore();
        });
    }

    public function provides(): array
    {
        return ['sampleshq'];
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
