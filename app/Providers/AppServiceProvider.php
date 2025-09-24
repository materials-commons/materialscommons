<?php

namespace App\Providers;

use App\Http\Sanitizers\DirectoryPathSanitizer;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Observers\FileObserver;
use App\Observers\UserObserver;
use App\Services\GoogleSheetsService;
use App\Services\FileServices\FileConversionService;
use App\Services\FileServices\FileMoveService;
use App\Services\FileServices\FilePathService;
use App\Services\FileServices\FileRenameService;
use App\Services\FileServices\FileReplicationService;
use App\Services\FileServices\FileStorageService;
use App\Services\FileServices\FileThumbnailService;
use App\Services\FileServices\FileVersioningService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

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

        File::observe(FileObserver::class);
        User::observe(UserObserver::class);

        Collection::macro('paginate', function ($perPage = 100) {
            $page = LengthAwarePaginator::resolveCurrentPage('page');

            return new LengthAwarePaginator($this->forPage($page, $perPage), $this->count(), $perPage, $page, [
                'path'  => LengthAwarePaginator::resolveCurrentPath(),
                'query' => request()->query(),
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GoogleSheetsService::class, function ($app) {
            return new GoogleSheetsService();
        });
        $this->app->singleton(FilePathService::class);
        $this->app->singleton(FileStorageService::class);
        $this->app->singleton(FileReplicationService::class);
        $this->app->singleton(FileConversionService::class);
        $this->app->singleton(FileThumbnailService::class);
        $this->app->singleton(FileVersioningService::class);
        $this->app->singleton(FileMoveService::class);
        $this->app->singleton(FileRenameService::class);
    }
}
