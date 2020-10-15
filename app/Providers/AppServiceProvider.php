<?php

namespace App\Providers;

use App\Http\Sanitizers\DirectoryPathSanitizer;
use App\Models\File;
use App\Models\Project;
use App\Observers\FileObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use YlsIdeas\FeatureFlags\Facades\Features;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Sanitizer::extend('normalizePath', DirectoryPathSanitizer::class);

        Blade::if('public', function (Project $project) {
            if (Features::accessible('public-projects')) {
                return $project->is_public;
            }

            return false;
        });

        File::observe(FileObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
