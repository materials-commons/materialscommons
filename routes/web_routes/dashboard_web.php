<?php

use App\Http\Controllers\Web\Dashboard\Admin\MCFS\IndexMCFSWebController;
use App\Http\Controllers\Web\Dashboard\ArchiveProjectOnDashboardWebController;
use App\Http\Controllers\Web\Dashboard\ImmediatelyDestroyProjectWebController;
use App\Http\Controllers\Web\Dashboard\IndexArchivedProjectsOnDashboardWebController;
use App\Http\Controllers\Web\Dashboard\IndexGlobusBookmarksWebController;
use App\Http\Controllers\Web\Dashboard\IndexTrashWebController;
use App\Http\Controllers\Web\Dashboard\MarkProjectAsActiveOnDashboardWebController;
use App\Http\Controllers\Web\Dashboard\RestoreProjectFromTrashWebController;
use App\Http\Controllers\Web\Dashboard\ShowDashboardDataDictionaryWebController;
use App\Http\Controllers\Web\Dashboard\ShowDashboardProjectsWebController;
use App\Http\Controllers\Web\Dashboard\ShowDashboardPublishedDatasetsWebController;
use App\Http\Controllers\Web\Dashboard\UnarchiveProjectOnDashboardWebController;
use App\Http\Controllers\Web\Dashboard\UnmarkProjectAsActiveOnDashboardWebController;
use Illuminate\Support\Facades\Route;

Route::redirect('/dashboard', '/app/dashboard/projects')->name('dashboard');

Route::get('/dashboard/projects', ShowDashboardProjectsWebController::class)
     ->name('dashboard.projects.show');

Route::get('/dashboard/projects/archived', IndexArchivedProjectsOnDashboardWebController::class)
     ->name('dashboard.projects.archived.index');

Route::get('/dashboard/projects/trash', IndexTrashWebController::class)
     ->name('dashboard.projects.trash.index');

Route::get('/dashboard/projects/{project}/trash/restore', RestoreProjectFromTrashWebController::class)
     ->name('dashboard.projects.trash.restore');

Route::get('/dashboard/published-datasets', ShowDashboardPublishedDatasetsWebController::class)
     ->name('dashboard.published-datasets.show');

Route::get('/dashboard/data-dictionary', ShowDashboardDataDictionaryWebController::class)
     ->name('dashboard.data-dictionary.show');

Route::get('/dashboard/admin/mcfs/index', IndexMCFSWebController::class)
     ->name('dashboard.admin.mcfs.index');

Route::get('/dashboard/projects/{project}/mark-as-active', MarkProjectAsActiveOnDashboardWebController::class)
     ->name('dashboard.projects.mark-as-active');

Route::get('/dashboard/projects/{project}/unmark-as-active', UnmarkProjectAsActiveOnDashboardWebController::class)
     ->name('dashboard.projects.unmark-as-active');

Route::get('/dashboard/projects/{project}/archive', ArchiveProjectOnDashboardWebController::class)
     ->name('dashboard.projects.archive');

Route::get('/dashboard/projects/{project}/unarchive', UnarchiveProjectOnDashboardWebController::class)
     ->name('dashboard.projects.unarchive');

Route::delete('/dashboards/projects/{project}/trash/immediately-destroy', ImmediatelyDestroyProjectWebController::class)
     ->name('dashboard.projects.trash.immediately-destroy');
