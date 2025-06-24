<?php

namespace App\Providers;

use App\Http\Sanitizers\DirectoryPathSanitizer;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Observers\FileObserver;
use App\Observers\UserObserver;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\Builder;

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
        User::observe(UserObserver::class);

        Collection::macro('paginate', function ($perPage = 100) {
            $page = LengthAwarePaginator::resolveCurrentPage('page');

            return new LengthAwarePaginator($this->forPage($page, $perPage), $this->count(), $perPage, $page, [
                'path'  => LengthAwarePaginator::resolveCurrentPath(),
                'query' => request()->query(),
            ]);
        });


        Builder::macro('toSearchQuery', function () {
            /** @var Builder $this */
            return [
                'index'   => $this->index,
                'query'   => $this->query,
                'filters' => $this->wheres,
                'limit'   => $this->limit,
            ];
        });

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
