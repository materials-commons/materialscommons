<?php

use App\Http\Controllers\Web\Dashboard\IndexGlobusBookmarksWebController;
use App\Http\Controllers\Web\Dashboard\MarkProjectAsActiveOnDashboardWebController;
use App\Http\Controllers\Web\Dashboard\ShowDashboardDataDictionaryWebController;
use App\Http\Controllers\Web\Dashboard\ShowDashboardProjectsWebController;
use App\Http\Controllers\Web\Dashboard\ShowDashboardPublishedDatasetsWebController;
use App\Http\Controllers\Web\Dashboard\UnmarkProjectAsActiveOnDashboardWebController;
use Illuminate\Support\Facades\Route;

Route::redirect('/dashboard', '/app/dashboard/projects')->name('dashboard');

Route::get('/dashboard/projects', ShowDashboardProjectsWebController::class)
     ->name('dashboard.projects.show');

Route::get('/dashboard/published-datasets', ShowDashboardPublishedDatasetsWebController::class)
     ->name('dashboard.published-datasets.show');

Route::get('/dashboard/data-dictionary', ShowDashboardDataDictionaryWebController::class)
     ->name('dashboard.data-dictionary.show');

Route::get('/dashboard/globus-bookmarks', IndexGlobusBookmarksWebController::class)
     ->name('dashboard.globus-bookmarks.index');

Route::get('/dashboard/project/{project}/mark-as-active', MarkProjectAsActiveOnDashboardWebController::class)
     ->name('dashboard.project.mark-as-active');

Route::get('/dashboard/project/{project}/unmark-as-active', UnmarkProjectAsActiveOnDashboardWebController::class)
     ->name('dashboard.project.unmark-as-active');
